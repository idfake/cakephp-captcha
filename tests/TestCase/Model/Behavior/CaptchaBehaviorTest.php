<?php
namespace Captcha\Test\Model\Behavior;

use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

class CaptchaBehaviorTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Captcha.Captchas', 'plugin.Captcha.Comments'
	];

	/**
	 * @var \Cake\ORM\Table;
	 */
	public $Captchas;

	/**
	 * @var \Cake\ORM\Table;
	 */
	public $Comments;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Configure::write('Captcha', [
				'debug' => false,
			]
		);

		$this->request = new Request();
		$this->request->env('REMOTE_ADDR', '127.0.0.1');
		$this->session = $this->getMockBuilder(Session::class)->setMethods(['id'])->getMock();
		$this->session->expects($this->once())->method('id')->willReturn(1);
		$this->request->session($this->session);
		Router::pushRequest($this->request);
		Router::reload();

		$this->Captchas = TableRegistry::get('Captcha.Captchas');

		$this->Comments = TableRegistry::get('Captcha.Comments');
		$this->Comments->addBehavior('Captcha.Captcha');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Comments, $this->Captchas);
		TableRegistry::clear();
	}

	/**
	 * @return void
	 */
	public function testSave() {
		$captcha = $this->Captchas->newEntity([
			'result' => 3,
			'ip' => '127.0.0.1',
			'session_id' => 1,
			'created' => new \DateTime('- 1 hour'),
			'modified' => new \DateTime('- 1 hour')
		]);
		$result = $this->Captchas->save($captcha);
		$this->assertTrue((bool)$result);
		$id = $captcha->id;

		$data = [
			'comment' => 'Foo'
		];
		$comment = $this->Comments->newEntity($data);
		$res = $this->Comments->save($comment);
		$this->assertFalse((bool)$res);

		$data['captcha_id'] = $id;
		$data['captcha_result'] = 2;
		$data['email_homepage'] = '';

		$comment = $this->Comments->newEntity($data);
		$res = $this->Comments->save($comment);
		$this->assertFalse((bool)$res);

		$data['captcha_result'] = 3;
		$data['email_homepage'] = '';

		$comment = $this->Comments->newEntity($data);
		$res = $this->Comments->save($comment);
		$this->assertTrue((bool)$res);

		$captcha = $this->Captchas->get($id);
		$this->assertNotEmpty($captcha->used);
	}

}