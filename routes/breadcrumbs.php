<?php
use App\Models\Customer;
use App\Models\Site;
use App\Models\Installation;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
 
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('customer.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Customers', route('customer.index'));
});

Breadcrumbs::for('site.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Sites', route('site.index'));
});

Breadcrumbs::for('installation.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Installations', route('installation.index'));
});

Breadcrumbs::for('customer.show', function (BreadcrumbTrail $trail, Customer $customer): void {
    $trail->parent('customer.index');
    $trail->push($customer->customer_name, route('customer.show', $customer));
});

Breadcrumbs::for('site.show', function (BreadcrumbTrail $trail, Site $site): void {
    $customer = Customer::find($site->account_id);
    $trail->parent('customer.show', $customer);
    $trail->push($site->site_name, route('site.show', $site));
});

Breadcrumbs::for('installation.show', function (BreadcrumbTrail $trail, Installation $installation): void {
    $site = Site::find($installation->site_id);
    $trail->parent('site.show', $site);
    $trail->push($installation->asset_id, route('installation.show', $installation));
});

Breadcrumbs::for('installation.create', function (BreadcrumbTrail $trail, Site $site): void {
    $trail->parent('site.show', $site);
    $trail->push('Create Installation', route('installation.create', $site));
});

Breadcrumbs::for('user.show', function (BreadcrumbTrail $trail): void {
    $trail->push('User Profile', route('user.show'));
});