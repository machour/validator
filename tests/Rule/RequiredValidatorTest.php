<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\tests\framework\validators;

use Yiisoft\Validators\Required;
use yii\tests\data\validators\models\FakedValidationModel;
use yii\tests\TestCase;

/**
 * @group validators
 */
class RequiredValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // destroy application, Validator must work without $this->app
        $this->destroyApplication();
    }

    public function testValidateValueWithDefaults()
    {
        $val = new Required();
        $this->assertFalse($val->validate(null));
        $this->assertFalse($val->validate([]));
        $this->assertTrue($val->validate('not empty'));
        $this->assertTrue($val->validate(['with', 'elements']));
    }

    public function testValidateValueWithValue()
    {
        $val = new Required(['requiredValue' => 55]);
        $this->assertTrue($val->validate(55));
        $this->assertTrue($val->validate('55'));
        $this->assertFalse($val->validate('should fail'));
        $this->assertTrue($val->validate(true));
        $val->strict = true;
        $this->assertTrue($val->validate(55));
        $this->assertFalse($val->validate('55'));
        $this->assertFalse($val->validate('0x37'));
        $this->assertFalse($val->validate('should fail'));
        $this->assertFalse($val->validate(true));
    }

    public function testValidateAttribute()
    {
        // empty req-value
        $val = new Required();
        $m = FakedValidationModel::createWithAttributes(['attr_val' => null]);
        $val->validateAttribute($m, 'attr_val');
        $this->assertTrue($m->hasErrors('attr_val'));
        $this->assertNotFalse(stripos(current($m->getErrors('attr_val')), 'blank'));
        $val = new Required(['requiredValue' => 55]);
        $m = FakedValidationModel::createWithAttributes(['attr_val' => 56]);
        $val->validateAttribute($m, 'attr_val');
        $this->assertTrue($m->hasErrors('attr_val'));
        $this->assertNotFalse(stripos(current($m->getErrors('attr_val')), 'must be'));
        $val = new Required(['requiredValue' => 55]);
        $m = FakedValidationModel::createWithAttributes(['attr_val' => 55]);
        $val->validateAttribute($m, 'attr_val');
        $this->assertFalse($m->hasErrors('attr_val'));
    }
}