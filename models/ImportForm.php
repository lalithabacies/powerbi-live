<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ImportForm is the model behind the Import form.
 */
class ImportForm extends Model
{
    public $file;
	public $prefix;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['file','prefix'], 'required'],
            // email has to be a valid email address
            ['file', 'file'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Upload File',
			'prefix' => 'A Unique Prefix'
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('uploads/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}
