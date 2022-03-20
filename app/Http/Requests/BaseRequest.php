<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


class BaseRequest extends FormRequest
{


    protected $rules;
    protected $message;

    public function rules()
    {
        return [];
    }

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [];

    /**
     * 设置当前验证场景
     * @var array
     */
    protected $currentScene = null;


    /**
     * 场景需要验证的规则
     * @var array
     */
    protected $only = [];


    /**
     * 设置验证场景
     * @access public
     * @param string $name 场景名
     * @return $this
     */
    public function scene(string $name)
    {
        // 设置当前场景
        $this->currentScene = $name;

        return $this;
    }

    /**
     * 获取数据验证的场景
     * @param string $scene
     * @return bool
     */
    protected function getScene(string $scene = ''): bool
    {
        if (empty($scene)) {
            //读取指定场景
            $scene = $this->currentScene;
        }
        if (empty($scene)) {
            return true;
        }
        $this->only = [];
        if (!isset($this->scene[$scene])) {
            return  false;
        }
        // 如果设置了验证适用场景
        $scene = $this->scene[$scene];
        if (is_string($scene)) {
            $scene = explode(',', $scene);
        }

        //将场景需要验证的字段填充入only
        $this->only = $scene;
        return true;
    }

    public function validateResolved()
    {
        list($class, $method) = explode('@', \request()->route()->getActionName());
        $this->scene($method);
        $this->rules = $this->rules();
        $this->message = $this->messages();
        if (!$this->getScene()) {
            throw new HttpException(422,'验证器场景不存在');
        }
        //如果场景需要验证的规则不为空
        if (!empty($this->only)) {
            $new_rules = [];
            foreach ($this->only as  $value) {
                if (array_key_exists($value,$this->rules)) {
                    $new_rules[$value] = $this->rules[$value];
                }
            }
            $this->rules = $new_rules;
        }


        $validator=$this->getValidatorInstance();
        //验证失败
        if ($validator->fails()) {
            $this->failedValidation($validator);
        }

    }


    protected function getValidatorInstance(){
        return \Illuminate\Support\Facades\Validator::make(request()->all(),$this->rules,$this->message,$this->attributes());
    }



    public function failedValidation(Validator $validator)
    {
        throw new \Dingo\Api\Exception\StoreResourceFailedException('给定的数据是无效的', $validator->errors());
    }
}
