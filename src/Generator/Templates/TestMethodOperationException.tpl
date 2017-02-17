	/**
	 * @expectedException \___ILLEGAL_TRANSITION_EXCEPTION_FQCN___
	 */
	public function test___TEST___()
	{
		try
		{
			$this->___OBJECT___->___OPERATION___();
		}
		catch(___ILLEGAL_TRANSITION_EXCEPTION_NAME___ $e)
		{
			$this->assertEquals('___DESIRED_STATE___', $e->getDesiredState());

			throw $e;
		}
	}
