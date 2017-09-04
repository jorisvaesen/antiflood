<?php
namespace JorisVaesen\Antiflood\Model\Table;

use App\Events\TicketListener;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Search\Manager;

/**
 * Antiflood Model
 *
 *
 * @method \JorisVaesen\Antiflood\Entity\Antiflood get($primaryKey, $options = [])
 * @method \JorisVaesen\Antiflood\Entity\Antiflood newEntity($data = null, array $options = [])
 * @method \JorisVaesen\Antiflood\Entity\Antiflood[] newEntities(array $data, array $options = [])
 * @method \JorisVaesen\Antiflood\Entity\Antiflood|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \JorisVaesen\Antiflood\Entity\Antiflood patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \JorisVaesen\Antiflood\Entity\Antiflood patchEntities($entities, array $data, array $options = [])
 * @method \JorisVaesen\Antiflood\Entity\Antiflood findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AntifloodsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('antifloods');
        $this->setDisplayField('identifier');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        return $rules;
    }
}
