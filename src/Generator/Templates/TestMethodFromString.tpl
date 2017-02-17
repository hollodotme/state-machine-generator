	public function testCanBuildStateFromStringRepresentation()
	{
		$state = ___ABSTRACT_STATE_CLASS_NAME___::fromString('___STATE_STRING_REPRESENTATION___');

		$this->assertInstanceOf(___STATE_CLASS_NAME___::class, $state);
	}

	/**
	 * @expectedException \___INVALID_STATE_STRING_EXCEPTION_FQCN___
	 */
	public function testInvalidStateStringThrowsException()
	{
		try
		{
			___ABSTRACT_STATE_CLASS_NAME___::fromString('___STATE_STRING_REPRESENTATION___.invalid');
		}
		catch (___INVALID_STATE_STRING_EXCEPTION_NAME___ $e)
		{
			$this->assertEquals('___STATE_STRING_REPRESENTATION___.invalid', $e->getStateString());

			throw $e;
		}
	}
