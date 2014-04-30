<?php
class MBlog extends YActiveRecord
{
    static protected $table = "blog";

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('blogCategoryId, title, content', 'required'),
            array('deleteFlag', 'safe'),
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
            'blogCategory'=>array(self::HAS_ONE, 'MBlogCategory', array('blogCategoryId'=>'blogCategoryId')),
        );
    }

    public function scopes(){
        return array(
            'adminBlogIndex'=>array(
                'select' => 'blogId,blogCategoryId,title,content,recordTime,deleteFlag',
            ),
            'blogIndex'=>array(
                'select' => 'blogId,blogCategoryId,title,content,recordTime',
                'condition' => 't.deleteFlag=0',
            ),
        );
    }

    public function dealScene(){
        $this->sceneParams['createTime'] = formatTime(getTime() - $this->recordTime,4);
        if($this->scene=='adminBlogIndex'){

        }elseif($this->scene=='adminBlogIndex'){

        }
    }
}