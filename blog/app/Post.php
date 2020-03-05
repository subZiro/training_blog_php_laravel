<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    //protected $fillable = ['title', 'content', 'image', 'date', 'description', 'category_id'];
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
        $post->user_id = 1;
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
        if($image == null) {return;}
        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        // запрос изображения, если отсутствует то вернуть подготовленое
        if($this->image == null) {return '/img/no-image.png';}

        return '/uploads/' . $this->image;
    }

    public function removeImage()
    {
        // удаление изображения
        if($this->image != null)
        {
            Storage::delete('uploads/' . $this->image);
        }
    }

    public function setCategory($id)
    {
        // получить категории из бд
        if($id == null) {return;}

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        // получить теги из бд
        if($ids == null) {return;}

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
        if($value == null)
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
        if($value == null)
        {
            return $this->setStandart();
        }
        
        return $this->setFeatured();
    }

/*
    public function setDateAttribute($value)
    {
        // изменение формата даты для записи в бд
        $date = Carbon::createFromFormat('dd/mm/yyyy', $value)->format('Y-m-d');
        $this->attribute['date'] = $date; // не записывает в базу
    }

    public function getDateAttribute($value)
    {
        // изменение формата даты для вывода
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }
*/


    public function getCategoryTitle()
    {
        // получение категории поста
        if($this->category != null)
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
        if($this->category != null) {return true;} 

        return false;
    }

    public static function getPopularposts()
    {
        // получение 2 популяных постов
        return self::orderBy('views', 'desc')->take(2)->get();
    }

    public static function getFeaturedPosts()
    {
        // получение 3 рекомендованных постов
        return self::where('is_featured', 1)->take(3)->get();
    }

    public static function getNewPosts()
    {
        // получение новых постов
        return self::orderBy('date', 'desc')->take(4)->get();
    }





}