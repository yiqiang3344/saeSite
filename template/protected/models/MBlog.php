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
            array('title, content', 'required'),
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
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    public function scopes(){
        return array(
            'blogIndex'=>array(
                'select' => 'blogId,title,content,recordTime',
            ),
        );
    }

    public function dealScene(){
        if($this->scene=='blogIndex'){
            $this->sceneParams['createTime'] = formatTime($this->recordTime-getTime(),4);
        }
    }
}