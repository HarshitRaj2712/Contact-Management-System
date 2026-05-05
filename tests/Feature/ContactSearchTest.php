<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\ContactEmail;
use App\Models\ContactPhone;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_ajax_search_can_match_contact_phone_and_email(): void
    {
        $user = User::factory()->create();
        $contact = Contact::create([
            'user_id' => $user->id,
            'first_name' => 'Anna',
            'last_name' => 'Smith',
            'company' => 'Globex',
            'job_title' => 'Manager',
            'favorite' => false,
        ]);

        ContactPhone::create([
            'contact_id' => $contact->id,
            'phone_number' => '555-222-1111',
            'type' => 'mobile',
        ]);

        ContactEmail::create([
            'contact_id' => $contact->id,
            'email' => 'anna.smith@example.com',
            'type' => 'work',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/contacts?search=555-222-1111&ajax=1');

        $response->assertOk();
        $response->assertJsonPath('total', 1);
        $this->assertStringContainsString('Anna Smith', $response->json('html'));
    }

    public function test_contacts_can_be_filtered_by_category_and_tag(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'category_name' => 'Work',
        ]);
        $otherCategory = Category::create([
            'user_id' => $user->id,
            'category_name' => 'Personal',
        ]);

        $tag = Tag::create(['tag_name' => 'VIP']);
        $otherTag = Tag::create(['tag_name' => 'Client']);

        $matchingContact = Contact::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'first_name' => 'Taylor',
            'last_name' => 'Reed',
            'company' => 'Orbit Labs',
            'favorite' => true,
        ]);
        $matchingContact->tags()->attach($tag->id);

        $ignoredContact = Contact::create([
            'user_id' => $user->id,
            'category_id' => $otherCategory->id,
            'first_name' => 'Jordan',
            'last_name' => 'Cole',
            'company' => 'Northwind',
            'favorite' => false,
        ]);
        $ignoredContact->tags()->attach($otherTag->id);

        $response = $this->actingAs($user)
            ->getJson('/contacts?category_id=' . $category->id . '&tag_ids%5B%5D=' . $tag->id . '&ajax=1');

        $response->assertOk();
        $response->assertJsonPath('total', 1);
        $this->assertStringContainsString('Taylor Reed', $response->json('html'));
        $this->assertStringNotContainsString('Jordan Cole', $response->json('html'));
    }

    public function test_dashboard_exposes_summary_widget_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'category_name' => 'Family',
        ]);

        $favoriteContact = Contact::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'first_name' => 'Mia',
            'last_name' => 'Stone',
            'birthday' => now()->addDays(5)->toDateString(),
            'favorite' => true,
        ]);

        $recentContact = Contact::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'first_name' => 'Noah',
            'last_name' => 'Price',
            'birthday' => now()->addDays(40)->toDateString(),
            'favorite' => false,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('totalContacts', 2);
        $response->assertViewHas('favoriteContacts', 1);
        $response->assertViewHas('categoriesCount', 1);
        $response->assertViewHas('upcomingBirthdays', function ($collection) use ($favoriteContact) {
            return $collection->count() === 1 && $collection->first()->is($favoriteContact);
        });
        $response->assertSee('Mia Stone');
        $response->assertSee('Noah Price');
    }
}
