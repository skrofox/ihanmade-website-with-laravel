<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ["name", "slug", "parent_id", "position"];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position')->orderBy('name');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }



    //Scope 

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('name');
    }

    // Tìm theo tên/slug
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('slug', 'like', "%{$term}%");
        });
    }
    /* ---------------- Helpers ---------------- */

    // Có phải root?
    public function getIsRootAttribute(): bool
    {
        return is_null($this->parent_id);
    }

    // Tạo slug tự động nếu chưa có (gọi khi tạo/sửa tuỳ ý)
    public static function booted(): void
    {
        static::saving(function (Category $cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }

    // Lấy breadcrumb (mảng từ root -> current)
    public function breadcrumbs(): array
    {
        $trail = [];
        $node = $this;
        while ($node) {
            array_unshift($trail, $node);
            $node = $node->parent;
        }
        return $trail;
    }

    // URL path dựa trên chuỗi slug (root/child/grandchild)
    public function slugPath(string $separator = '/'): string
    {
        return collect($this->breadcrumbs())
            ->pluck('slug')
            ->implode($separator);
    }
}
