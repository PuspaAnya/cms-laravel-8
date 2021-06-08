<?php

// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Dashboard', route('dashboard.index'));
});

// Dashboard > Home
Breadcrumbs::for('dashboard_home', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Home', '#');
});

// Dashboard > Categories
Breadcrumbs::for('categories', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Categories', route('categories.index'));
});

// Dashboard > Categories > Add
Breadcrumbs::for('add_category', function ($trail) {
    $trail->parent('categories');
    $trail->push('Add', route('categories.create'));
});

// Dashboard > Categories > Edit
Breadcrumbs::for('edit_category', function ($trail, $category) {
    $trail->parent('categories');
    $trail->push('Edit', route('categories.edit', ['category' => $category]));
});

// Dashboard > Categories > Edit > [title]
Breadcrumbs::for('edit_category_title', function ($trail, $category) {
    $trail->parent('edit_category', $category);
    $trail->push($category->title, route('categories.edit', ['category' => $category]));
});

// Dashboard > Categories > Detail
Breadcrumbs::for('detail_category', function ($trail, $category) {
    $trail->parent('categories');
    $trail->push('Detail', route('categories.show', ['category' => $category]));
});

// Dashboard > Categories > Detail > [title]
Breadcrumbs::for('detail_category_title', function ($trail, $category) {
    $trail->parent('detail_category', $category);
    $trail->push($category->title, route('categories.show', ['category' => $category]));
});

// Home > Blog
// Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
//     $trail->parent('home');
//     $trail->push('Blog', route('blog'));
// });

// Home > Blog > [Category]
// Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
//     $trail->parent('blog');
//     $trail->push($category->title, route('category', $category));
// });