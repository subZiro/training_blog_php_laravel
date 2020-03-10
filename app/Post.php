<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    protected $fillable = ['title','content', 'date', 'description'];

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
    	return $this->belongsToMany(
    		Tag::class,
    		'post_tags',
    		'post_id',
    		'tag_id'
    	);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        // удалить контент из папки
        $this->removeImage();
        $this->delete();
    }

    public function uploadImage($image)
    {
        // изменение поста
        if(is_null($image)) {return;}
        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        // запрос изображения, если отсутствует то вернуть подготовленое
        if(is_null($this->image)) {return '/img/no-image.png';}

        return '/uploads/' . $this->image;
    }

    public function removeImage()
    {
        // удаление изображения
        if(!is_null($this->image))
        {
            Storage::delete('uploads/' . $this->image);
        }
    }

    public function setCategory($id)
    {
        // получить категории из бд
        if(is_null($id)) {return;}

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        // получить теги из бд
        if(is_null($ids)) {return;}

        $this->tags()->sync($ids);
    }

    public function setDraft()
    {
        $this->status = 0;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = 1;
        $this->save();
    }

    public function toggleStatus($value)
    {
        // переключение статуса поста
        if(is_null($value))
        {
            return $this->setDraft();
        }

        return $this->setPublic();
    }

    public function setFeatured()
    {
        // рекомендация поста
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if(is_null($value))
        {
            return $this->setStandart();
        }
        
        return $this->setFeatured();
    }

    public function getCategoryTitle()
    {
        // получение категории поста
        if(!is_null($this->category))
        {
            return $this->category->title;
        }
        return 'Нет категории';
    }

    public function getTagsTitle()
    {
        // получение тегов, вывод форматирован строки
        if(!$this->tags->isEmpty())
        {
            return implode(', ', $this->tags->pluck('title')->all());
        }
        return 'нет Тегов';
    }

    public function getStringDate()
    {
        // даты форматированной строкой
        $date = Carbon::createFromFormat('Y-m-d', $this->date)->format('d F, Y');
        return $date;
    }

    public function hasPrevPost()
    {
        // проверка существует ли предыдущий пост
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevPost()
    {
        // получение предэдущего поста
        $postId = $this->hasPrevPost();  // id
        return self::find($postId);
    }


    public function hasNextPost()
    {
        // проверка существует ли следущий пост
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNextPost()
    {
        // получение следующего поста
        $postId = $this->hasNextPost();  // id
        return self::find($postId);
    }

    public function related()
    {
        // карусель постов. возвращает все посты кроме текущего
        return self::all()->except($this->id);
    }

    public function hasCategory()
    {
        // есть ли у поста категория
        if(is_null($this->category)) {return true;} 

        return false;
    }

    public static function getPopularposts()
    {
        // получение 2 популяных постов
        return self::orderBy('views', 'desc')->where('status', 1)->take(2)->get();
    }

    public static function getFeaturedPosts()
    {
        // получение 3 рекомендованных постов
        return self::where('is_featured', 1)->where('status', 1)->take(3)->get();
    }

    public static function getNewPosts()
    {
        // получение новых постов
        return self::orderBy('date', 'desc')->where('status', 1)->take(4)->get();
    }

    public function getComments()
    {
        // получение комментариев со статусом 1
        return $this->comments()->where('status', 1)->get();
    }
}