<?php namespace Gzero\Entity;

/**
 * This file is part of the GZERO CMS package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Class RouteTranslation
 *
 * @package    Gzero\Entity
 * @author     Adrian Skierniewski <adrian.skierniewski@gmail.com>
 * @copyright  Copyright (c) 2014, Adrian Skierniewski
 */
class RouteTranslation extends Base {

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'url',
        'is_active'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'is_active' => false
    ];

    /**
     * Lang reverse relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang()
    {
        return $this->belongsTo(Lang::class);
    }
}
