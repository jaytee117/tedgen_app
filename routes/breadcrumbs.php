<?php

use App\Models\Customer;
use App\Models\Site;
use App\Models\Installation;
use App\Models\DataLine;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('show.login', function (BreadcrumbTrail $trail): void {
    $trail->push('Login', route('show.login'));
});

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('customer.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push('Customers', route('customer.index'));
});

Breadcrumbs::for('site.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push('Sites', route('site.index'));
});

Breadcrumbs::for('installation.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push('Installations', route('installation.index'));
});

Breadcrumbs::for('customer.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push('Create Customer', route('customer.create'));
});

Breadcrumbs::for('customer.show', function (BreadcrumbTrail $trail, Customer $customer): void {
    $trail->parent('dashboard');
    $trail->push($customer->customer_name, route('customer.show', $customer));
});

Breadcrumbs::for('site.show', function (BreadcrumbTrail $trail, Site $site): void {
    $customer = Customer::find($site->account_id);
    $trail->parent('customer.show', $customer);
    $trail->push($site->site_name, route('site.show', $site));
});

Breadcrumbs::for('site.create', function (BreadcrumbTrail $trail, Customer $customer): void {
    $trail->parent('customer.show', $customer);
    $trail->push('Create Site', route('site.create', $customer));
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

Breadcrumbs::for('dataline.show', function (BreadcrumbTrail $trail, DataLine $dataline): void {
    $installation = Installation::find($dataline->installation_id);
    $trail->parent('installation.show', $installation);
    $trail->push($dataline->line_reference, route('dataline.show', $dataline));
});

Breadcrumbs::for('user.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push('User Profile', route('user.show'));
});
