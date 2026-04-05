<?php

declare(strict_types=1);

namespace App\Providers;

use App\CustomObservers\CustomStatementObserver;
use App\Enums\StatusTransitionType;
use App\Jobs\Dispatchers\StatementNotificationJobDispatcher;
use App\Models\Statement;
use App\Services\StatementService\Strategies\AdminApproveTransitionStrategy;
use App\Services\StatementService\Strategies\AdminRejectTransitionStrategy;
use App\Services\StatementService\Strategies\ClientSubmitTransitionStrategy;
use Database\Seeders\EntityFactory\EntitySeedFactory;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StatementNotificationJobDispatcher::class);
        $this->app->singleton(EntitySeedFactory::class);
        $this->app->singleton(
            'status_transition.' . StatusTransitionType::SUBMIT->value,
            ClientSubmitTransitionStrategy::class,
        );
        $this->app->singleton(
            'status_transition.' . StatusTransitionType::APPROVE->value,
            AdminApproveTransitionStrategy::class,
        );
        $this->app->singleton(
            'status_transition.' . StatusTransitionType::REJECT->value,
            AdminRejectTransitionStrategy::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::enablePasswordGrant();
        Statement::observeDomain(CustomStatementObserver::class);
    }
}
