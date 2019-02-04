<?php

namespace luya\news\models;

use luya\news\admin\Module;
use luya\admin\ngrest\base\NgRestModel;

/**
 * News Category Model
 *
 * @author Basil Suter <basil@nadar.io>
 */
class Cat extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public $i18n = ['title'];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_cat';
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_DELETE, [$this, 'eventBeforeDelete']);
    }
    
    /**
     * @inheritdoc
     */
    public function eventBeforeDelete($event)
    {
        if (count($this->articles) > 0 || count($this->children)) {
            $this->addError('id', Module::t('cat_delete_error'));
            $event->isValid = false;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Module::t('cat_title'),
            'parent_cat_id' => Module::t('cat_parent_cat_id'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['parent_cat_id', 'number', 'integerOnly' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-news-cat';
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'title' => 'text',
            'parent_cat_id' => [
                'selectModel',
                'modelClass' => self::className(),
                'valueField' => 'id',
                'labelField' => 'title',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            [['create', 'update'], ['title', 'parent_cat_id']],
            [['list'], ['title']],
            [['delete'], true],
        ];
    }
    
    /**
     * Get articles for this category.
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['cat_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_cat_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_cat_id' => 'id']);
    }

    public function ngRestRelations()
    {
        return [
           ['label' => 'Articles', 'apiEndpoint' => Article::ngRestApiEndpoint(), 'dataProvider' => $this->getArticles()],
        ];
    }
}
