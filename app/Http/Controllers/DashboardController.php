<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display dashboard widgets and recent contact activity.
     */
    public function index(): View
    {
        $user = auth()->user();

        $contactQuery = Contact::query()->forUser($user->id);

        $totalContacts = (clone $contactQuery)->count();
        $favoriteContacts = (clone $contactQuery)->where('favorite', true)->count();
        $categoriesCount = $user->categories()->count();
        $upcomingBirthdays = $this->upcomingBirthdays($user->id);
        $recentContacts = (clone $contactQuery)
            ->with('category')
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'first_name', 'last_name', 'company', 'birthday', 'category_id', 'created_at', 'favorite']);

        return view('dashboard', compact(
            'totalContacts',
            'favoriteContacts',
            'categoriesCount',
            'upcomingBirthdays',
            'recentContacts'
        ));
    }

    /**
     * Get contacts with birthdays in the next 30 days.
     */
    private function upcomingBirthdays(int $userId)
    {
        $windowEnd = now()->addDays(30);

        return Contact::query()
            ->forUser($userId)
            ->whereNotNull('birthday')
            ->with('category')
            ->get(['id', 'first_name', 'last_name', 'birthday', 'company', 'category_id'])
            ->filter(function (Contact $contact) use ($windowEnd) {
                $birthday = Carbon::parse($contact->birthday)->setYear(now()->year);

                if ($birthday->lt(now()->startOfDay())) {
                    $birthday->addYear();
                }

                return $birthday->between(now()->startOfDay(), $windowEnd->copy()->endOfDay());
            })
            ->sortBy(function (Contact $contact) {
                $birthday = Carbon::parse($contact->birthday)->setYear(now()->year);

                if ($birthday->lt(now()->startOfDay())) {
                    $birthday->addYear();
                }

                return $birthday->timestamp;
            })
            ->values()
            ->take(5);
    }
}
