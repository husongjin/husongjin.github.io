<?php

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers MediaWiki\Linker\LinkRendererFactory
 */
class LinkRendererFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var NamespaceInfo
	 */
	private $nsInfo;

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @var HookContainer
	 */
	private $hookContainer;

	protected function setUp(): void {
		parent::setUp();

		$this->titleFormatter = $this->createMock( TitleFormatter::class );
		$this->linkCache = $this->createMock( LinkCache::class );
		$this->nsInfo = $this->createMock( NamespaceInfo::class );
		$this->specialPageFactory = $this->createMock( SpecialPageFactory::class );
		$this->hookContainer = $this->createMock( HookContainer::class );
	}

	public static function provideCreateFromLegacyOptions() {
		return [
			[
				[ 'forcearticlepath' ],
				'getForceArticlePath',
				true
			],
			[
				[ 'http' ],
				'getExpandURLs',
				PROTO_HTTP
			],
			[
				[ 'https' ],
				'getExpandURLs',
				PROTO_HTTPS
			],
			[
				[ 'stubThreshold' => 150 ],
				'getStubThreshold',
				150
			],
		];
	}

	/**
	 * @dataProvider provideCreateFromLegacyOptions
	 */
	public function testCreateFromLegacyOptions( $options, $func, $val ) {
		$factory = new LinkRendererFactory(
				$this->titleFormatter, $this->linkCache, $this->nsInfo,
				$this->specialPageFactory, $this->hookContainer
			);
		$linkRenderer = $factory->createFromLegacyOptions(
			$options
		);
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertEquals( $val, $linkRenderer->$func(), $func );
	}

	public function testCreate() {
		$factory = new LinkRendererFactory(
			$this->titleFormatter, $this->linkCache, $this->nsInfo,
			$this->specialPageFactory, $this->hookContainer
		);
		$this->assertInstanceOf( LinkRenderer::class, $factory->create() );
	}

	public function testCreateForUser() {
		/** @var MockObject|User $user */
		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getStubThreshold' ] )->getMock();
		$user->expects( $this->once() )
			->method( 'getStubThreshold' )
			->willReturn( 15 );
		$factory = new LinkRendererFactory(
			$this->titleFormatter, $this->linkCache, $this->nsInfo,
			$this->specialPageFactory, $this->hookContainer
		);
		$linkRenderer = $factory->createForUser( $user );
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertEquals( 15, $linkRenderer->getStubThreshold() );
	}
}
