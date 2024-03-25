<?php

namespace App\Application\Command;

use DateTime;

Class CreateWod
{
	private string $wod;

	private string $type;

	private string $wodDate;

	public function __construct(string $wod, string $type, string $wodDate)
	{
		$this->wod = $wod;
		$this->type = $type;
		$this->wodDate = $wodDate;
	}

	public function getWod(): string
	{
		return $this->wod;
	}
	
	public function getWodDate(): string
	{
		return $this->wodDate;
	}

	public function getType(): string
	{
		return $this->type;
	}

}