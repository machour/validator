<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\tests\framework\validators;

use Yiisoft\Validators\RegularExpression;
use yii\tests\data\validators\models\FakedValidationModel;
use yii\tests\TestCase;

/**
 * @group validators
 */
class RegularExpressionValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // destroy application, Validator must work without $this->app
        $this->destroyApplication();
    }

    public function testValidateValue()
    {
        $val = new RegularExpression(['pattern' => '/^[a-zA-Z0-9](\.)?([^\/]*)$/m']);
        $this->assertTrue($val->validate('b.4'));
        $this->assertFalse($val->validate('b./'));
        $this->assertFalse($val->validate(['a', 'b']));
        $val->not = true;
        $this->assertFalse($val->validate('b.4'));
        $this->assertTrue($val->validate('b./'));
        $this->assertFalse($val->validate(['a', 'b']));
    }

    public function testValidateAttribute()
    {
        $val = new RegularExpression(['pattern' => '/^[a-zA-Z0-9](\.)?([^\/]*)$/m']);
        $m = FakedValidationModel::createWithAttributes(['attr_reg1' => 'b.4']);
        $val->validateAttribute($m, 'attr_reg1');
        $this->assertFalse($m->hasErrors('attr_reg1'));
        $m->attr_reg1 = 'b./';
        $val->validateAttribute($m, 'attr_reg1');
        $this->assertTrue($m->hasErrors('attr_reg1'));
    }

    public function testMessageSetOnInit()
    {
        $val = new RegularExpression(['pattern' => '/^[a-zA-Z0-9](\.)?([^\/]*)$/m']);
        $this->assertInternalType('string', $val->message);
    }

    public function testInitException()
    {
        $this->expectException('yii\exceptions\InvalidConfigException');
        $val = new RegularExpression();
        $val->validate('abc');
    }
}