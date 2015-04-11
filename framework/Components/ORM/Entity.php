<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\Components\ORM;

use Spiral\Components\ORM\Schemas\EntitySchema;
use Spiral\Support\Models\DataEntity;

class Entity extends DataEntity
{
    /**
     * ORM requested schema analysis. This constant will be send as option while analysis.
     */
    const SCHEMA_ANALYSIS = 788;

    /**
     * Model specific constant to indicate that model has to be validated while saving. You still can
     * change this behaviour manually by providing argument to save method.
     */
    const FORCE_VALIDATION = true;

    /**
     * Set this constant to false to disable automatic column, index and foreign keys creation.
     * By default entities will read schema from database, so you can connect your ORM model to
     * already existed table.
     */
    const ACTIVE_SCHEMA = true;


    /**
     * TODO!!!!
     */

    const HAS_ONE              = 101;
    const HAS_MANY             = 102;
    const BELONGS_TO           = 103;
    const MANY_TO_MANY         = 104;
    const MANY_THOUGHT         = 105;
    const MORPHED_HAS_ONE      = 106;
    const MORPHED_HAS_MANY     = 107;
    const BELONGS_TO_MORPHED   = 108;
    const MANY_TO_MANY_MORPHED = 109;
    const MORPHED_MANY_TO_MANY = 1010;

    /**
     * Key values.
     */
    const OUTER_KEY  = 901;
    const LOCAL_KEY  = 902;
    const OUTER_TYPE = 903;
    const LOCAL_TYPE = 904;

    const THOUGHT_TABLE = 905;
    const PIVOT_TABLE   = 905;
    const VIA_TABLE     = 905;

    const BACK_REF = 906;

    const CONSTRAINT        = 907;
    const CONSTRAINT_ACTION = 908;

    /**
     * Constants used to declare index type. See documentation for indexes property.
     */
    const INDEX  = 1000;
    const UNIQUE = 2000;

    /**
     * Already fetched schemas from ORM. Yes, ORM entity is really similar to ODM. Original ORM was
     * written long time ago before ODM and solutions i put to ORM was later used for ODM, while
     * "great transition" (tm) ODM was significantly updated and now ODM drive updates for ORM,
     * the student become the teacher.
     *
     * @var array
     */
    protected static $schemaCache = array();

    /**
     * Table associated with entity. Spiral will guess table name automatically based on class name
     * use Doctrine Inflector, however i'm STRONGLY recommend to declare table name manually as it
     * gives more readable code.
     *
     * @var string
     */
    protected $table = null;

    /**
     * Database name/id where entity table located in. By default database will be used if nothing
     * else is specified.
     *
     * @var string
     */
    protected $database = 'default';

    protected $schema = array();
    protected $indexes = array();

    public function __construct($fields = array())
    {
        if (!isset(self::$schemaCache[$class = get_class($this)]))
        {
            static::initialize();
            //            //self::$schemaCache[$class] = ORM::getInstance()->getSchema(get_class($this));
        }

        //Prepared document schema
        //$this->schema = self::$schemaCache[$class];

        //Merging with default values
        //$this->fields = $fields + $this->schema[ORM::E_DEFAULTS];
    }

    /**
     * Prepare document property before caching it ORM schema. This method fire event "property" and
     * sends SCHEMA_ANALYSIS option to trait initializers. Method can be used to create custom filters,
     * schema values and etc.
     *
     * @param EntitySchema $schema
     * @param string       $property Model property name.
     * @param mixed        $value    Model property value, will be provided in an inherited form.
     * @return mixed
     */
    public static function describeProperty(EntitySchema $schema, $property, $value)
    {
        static::initialize(self::SCHEMA_ANALYSIS);

        return static::dispatcher()->fire('describe', compact(
            'schema',
            'property',
            'value'
        ))['value'];
    }
}