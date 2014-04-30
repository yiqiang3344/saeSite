<?php
class MBlogCategory extends YActiveRecord
{
    static protected $table = "blogCategory";

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'required'),
            array('sort,deleteFlag', 'safe'),
        );
    }

    protected function beforeSave() {
        if($this->isNewRecord) {
            $this->recordTime = getTime();
        }
        $this->updateTime = null;
        return parent::beforeSave();
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'blogCount'=>array(self::STAT, 'MBlog', 'blogCategoryId'),
        );
    }

    public function scopes(){
    }

    public function dealScene(){
    }
}