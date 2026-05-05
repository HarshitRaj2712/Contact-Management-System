<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController extends Controller
{
    public function exportCsv(Request $request)
    {
        $user = $request->user();
        $contacts = $user->contacts()->with(['phones', 'emails', 'addresses', 'tags'])->get();

        $filename = 'contacts_export_' . now()->format('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($contacts) {
            $handle = fopen('php://output', 'w');
            // header
            fputcsv($handle, ['first_name','last_name','company','email','phone','birthday','category','tags','notes','favorite']);

            foreach ($contacts as $c) {
                $emails = $c->emails->pluck('email')->implode('|');
                $phones = $c->phones->pluck('number')->implode('|');
                $tags = $c->tags->pluck('tag_name')->implode('|');
                fputcsv($handle, [
                    $c->first_name,
                    $c->last_name,
                    $c->company,
                    $emails,
                    $phones,
                    $c->birthday,
                    optional($c->category)->category_name,
                    $tags,
                    $c->notes,
                    $c->favorite ? '1' : '0',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function exportPdf(Request $request)
    {
        // Simple HTML view of contacts. For real PDF output, install barryvdh/laravel-dompdf and render.
        $contacts = $request->user()->contacts()->with(['phones','emails','addresses','tags'])->get();
        return view('contacts.export_pdf', compact('contacts'));
    }

    public function importCsv(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');
        $header = null;
        $created = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (!$header) {
                $header = $row;
                continue;
            }
            $data = array_combine($header, $row);

            // Basic validation: at least first_name or email or phone
            if (empty($data['first_name']) && empty($data['email']) && empty($data['phone'])) {
                $skipped++;
                continue;
            }

            // Duplicate detection: same email or phone exists
            $exists = false;
            if (!empty($data['email'])) {
                $exists = $request->user()->contacts()->whereHas('emails', function ($q) use ($data) {
                    $q->where('email', $data['email']);
                })->exists();
            }
            if (!$exists && !empty($data['phone'])) {
                $exists = $request->user()->contacts()->whereHas('phones', function ($q) use ($data) {
                    $q->where('number', $data['phone']);
                })->exists();
            }

            if ($exists) {
                $skipped++;
                continue;
            }

            $contact = $request->user()->contacts()->create([
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'company' => $data['company'] ?? null,
                'birthday' => $data['birthday'] ?? null,
                'notes' => $data['notes'] ?? null,
                'favorite' => (!empty($data['favorite']) && $data['favorite'] == '1') ? 1 : 0,
            ]);

            // emails
            if (!empty($data['email'])) {
                $emails = explode('|', $data['email']);
                foreach ($emails as $e) {
                    $contact->emails()->create(['email' => trim($e)]);
                }
            }

            if (!empty($data['phone'])) {
                $phones = explode('|', $data['phone']);
                foreach ($phones as $p) {
                    $contact->phones()->create(['number' => trim($p)]);
                }
            }

            // tags
            if (!empty($data['tags'])) {
                $tagNames = array_filter(array_map('trim', explode('|', $data['tags'])));
                $tagIds = [];
                foreach ($tagNames as $name) {
                    $tag = \App\Models\Tag::firstOrCreate(['tag_name' => $name]);
                    $tagIds[] = $tag->id;
                }
                $contact->tags()->sync($tagIds);
            }

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'subject_type' => Contact::class,
                'subject_id' => $contact->id,
                'action' => 'imported',
                'changes' => json_encode($contact->toArray()),
            ]);

            $created++;
        }

        fclose($handle);

        return redirect()->route('contacts.index')->with('success', "Import complete: {$created} created, {$skipped} skipped.");
    }

    public function duplicates(Request $request)
    {
        // Simple duplicate detection based on identical emails or phone numbers
        $user = $request->user();

        $byEmail = Contact::select('id')->where('user_id', $user->id)
            ->whereHas('emails')
            ->with('emails')
            ->get()
            ->groupBy(function ($c) { return $c->emails->pluck('email')->implode('|'); })
            ->filter(function ($group) { return $group->count() > 1; });

        // Also check phone duplicates
        $byPhone = Contact::select('id')->where('user_id', $user->id)
            ->whereHas('phones')
            ->with('phones')
            ->get()
            ->groupBy(function ($c) { return $c->phones->pluck('number')->implode('|'); })
            ->filter(function ($group) { return $group->count() > 1; });

        return view('contacts.duplicates', ['byEmail' => $byEmail, 'byPhone' => $byPhone]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = array_filter((array) $request->input('contact_ids', []));
        if (empty($ids)) {
            return back()->with('warning', 'No contacts selected.');
        }

        $contacts = $request->user()->contacts()->whereIn('id', $ids)->get();
        foreach ($contacts as $c) {
            $c->delete();
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'subject_type' => Contact::class,
                'subject_id' => $c->id,
                'action' => 'deleted_bulk',
                'changes' => null,
            ]);
        }

        return back()->with('success', count($contacts) . ' contacts moved to trash.');
    }
}
