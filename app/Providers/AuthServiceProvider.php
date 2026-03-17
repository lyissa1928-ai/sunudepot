<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use App\Models\MaterialRequest;
use App\Models\AggregatedOrder;
use App\Models\Budget;
use App\Models\BudgetAllocation;
use App\Models\Asset;
use App\Models\MaintenanceTicket;
use App\Models\DeliverySlip;
use App\Models\Conversation;
use App\Models\InboxMessage;
use App\Policies\ConversationPolicy;
use App\Policies\InboxMessagePolicy;
use App\Policies\DeliverySlipPolicy;
use App\Policies\MaterialRequestPolicy;
use App\Policies\AggregatedOrderPolicy;
use App\Policies\BudgetPolicy;
use App\Policies\BudgetAllocationPolicy;
use App\Policies\AssetPolicy;
use App\Policies\MaintenanceTicketPolicy;

/**
 * AuthServiceProvider
 *
 * Register policies and gates for authorization
 * Integrates with Spatie/laravel-permission for role-based access
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        MaterialRequest::class => MaterialRequestPolicy::class,
        AggregatedOrder::class => AggregatedOrderPolicy::class,
        Budget::class => BudgetPolicy::class,
        BudgetAllocation::class => BudgetAllocationPolicy::class,
        Asset::class => AssetPolicy::class,
        MaintenanceTicket::class => MaintenanceTicketPolicy::class,
        DeliverySlip::class => DeliverySlipPolicy::class,
        Conversation::class => ConversationPolicy::class,
        InboxMessage::class => InboxMessagePolicy::class,
    ];

    /**
     * Register any application services
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot the authentication services for the application
     */
    public function boot(): void
    {
        App::setLocale('fr');

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
