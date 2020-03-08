<?php

namespace App\Providers;

use App\Post;
use App\Comment;
use App\Category;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // загрузка rightsidebar 
        $categories = Category::all();
        view()->composer('pages._rightSidebar', function($view){
            $view->with('popularPosts', Post::getPopularPosts());
            $view->with('featuredPosts', Post::getFeaturedposts());
            $view->with('newPosts', Post::getNewPosts());
            $view->with('categories', Category::all());
        });

        view()->composer('admin.sidebar', function($view){
            $view->with('newCommentsCount', Comment::where('status',0)->count());
        });

    }

}
