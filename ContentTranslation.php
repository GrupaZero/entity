<?php namespace Gzero\Entity;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * This file is part of the GZERO CMS package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Class ContentTranslation
 *
 * @package    Gzero\Model
 * @author     Adrian Skierniewski <adrian.skierniewski@gmail.com>
 * @copyright  Copyright (c) 2014, Adrian Skierniewski
 */
class ContentTranslation extends Base {

    use SoftDeletingTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'langCode',
        'title',
        'teaser',
        'body',
        'isActive'
    ];

    /**
     * Lang reverse relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lang()
    {
        return $this->belongsTo('\Gzero\Entity\Lang');
    }
}
