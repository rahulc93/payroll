<?php

class Controller_Salaries extends Controller_Template{

	public function action_index()
	{
		$data['salaries'] = Model_Salary::find('all');
		$this->template->title = "Salaries";
		$this->template->content = View::forge('salaries/index', $data);

    }

    public function action_payrollMonthSelected($id)
    {
		$salaryNew = Model_Salary::find($id);
		
		$salary = null;
		
		$salaryList = Model_Salary::find('all');
		
		foreach($salaryList as $salaryTemp)
		{
			if ($salaryTemp->employee_id == $salaryNew->employee_id and $salaryTemp->month == $salaryNew->month and $salaryTemp->year == $salaryNew->year)
			{
				$salary = $salaryTemp;
				break;
			}
		}
		
		if ($salary == null)
		{
			Session::set_flash('error', 'Salary record not found');
			Response::redirect('employees');
		}
		
		if ($salary->id == $id)
		{
			// no previous entry present
			$this->copyPrevMonth($salary->id);
			Session::set_flash('success', 'Existing entry not found. Successfully copied all data from previous month.');
		}
		
		else
		{
			// previous entry found
			Session::set_flash('success', 'Previous entry found for this entry. Enter fresh data');
			$salaryNew->delete();
		}
		
		Response::redirect('salaries/edit/'.$salary->id);
    }

    /*public function action_payrollCalculationType($month, $year)
    {
    	$data['salary'] = Model_Salary::find_by_month($month);
		$this->template->title = "Payroll Type";
		$this->template->content = View::forge('salaries/payrollCalculationType', $data);    
	}*/

	public function copyPrevMonth($id)
	{
		$salaryCurrMonth = Model_Salary::find($id);
		
		$salaryPrevMonth = null;
		
		$salaryList = Model_Salary::find('all');
		
		foreach($salaryList as $salaryTemp)
		{
			if ($salaryTemp->employee_id == $salaryCurrMonth->employee_id and $salaryTemp->month == ($salaryCurrMonth->month - 1) and $salaryTemp->year == $salaryCurrMonth->year)
			{
				$salaryPrevMonth = $salaryTemp;
				break;
			}
		}
		
		if ($salaryPrevMonth == null)
		{
			Session::set_flash('error', 'Salary entry for previous month not present. New data has to be entered.');
			//Response::redirect('salaries');
		}

		else
		{
			$salaryCurrMonth->gross = $salaryPrevMonth->gross;
			$salaryCurrMonth->sdxo = $salaryPrevMonth->sdxo;
			$salaryCurrMonth->adj_sdx = $salaryPrevMonth->adj_sdx;
			$salaryCurrMonth->pfv = $salaryPrevMonth->pfv;
			$salaryCurrMonth->medical = $salaryPrevMonth->medical;
			$salaryCurrMonth->travel = $salaryPrevMonth->travel;
			$salaryCurrMonth->leave = $salaryPrevMonth->leave;
			$salaryCurrMonth->bonus = $salaryPrevMonth->bonus;
			$salaryCurrMonth->misc1 = $salaryPrevMonth->misc1;
			$salaryCurrMonth->misc2 = $salaryPrevMonth->misc2;
			$salaryCurrMonth->prof_tax = $salaryPrevMonth->prof_tax;
			$salaryCurrMonth->inc_tax = $salaryPrevMonth->inc_tax;
			$salaryCurrMonth->other2 = $salaryPrevMonth->other2;
			$salaryCurrMonth->other3 = $salaryPrevMonth->other3;
			$salaryCurrMonth->other4 = $salaryPrevMonth->other4;
			$salaryCurrMonth->other5 = $salaryPrevMonth->other5;
		}
		
		$salaryCurrMonth->save();
		/*$data['employee'] = Model_Employee::find_by_employee_id($salaryCurrMonth->employee_id);
		Response::redirect('salaries/edit/'.$salaryCurrMonth->id);*/

	}

	public function action_view($id = null)
	{
	 	is_null($id) and Response::redirect('salaries');

	 	if ( ! $data['salary'] = Model_Salary::find($id))
	 	{
	  		Session::set_flash('error', 'Could not find salary #'.$id);
	  		Response::redirect('salaries');
  		}

	 	$this->template->title = "";
	 	$this->template->content = View::forge('salaries/view', $data);

 	}

 	public function action_addNew($employee_id, $month, $year)
 	{

 		$data['salary'] = Model_Salary::forge(array(
					   	'employee_id' => $employee_id,
					   	'gross' => 0,
					   	'sdxo' => 0,
					   	'adj_sdx' => 0,
					   	'pfv' => 0,
					   	'pf_adj' => 0,
					   	'basic' => 0,
					   	'hra' => 0,
					   	'lta' => 0,
					   	'medical' => 0,
					   	'travel' => 0,
					   	'pf' => 0,
					   	'other1' => 0,
					   	'leave' => 0,
					   	'bonus' => 0,
					   	'misc1' => 0,
					   	'misc2' => 0,
					   	'total1' => 0,
					   	'prof_tax' => 0, 
					   	'inc_tax' => 0,
					   	'other2' => 0,
					   	'other3' => 0,
					   	'other4' => 0,
					   	'other5' => 0,
					   	'total2' => 0,
					   	'net' => 0,
					   	'check' => 0,
					   	'pf_match' => 0,
					   	'gross_match' => 0,
					   	'month' => $month,
					   	'year' => $year
		));

		if ($data['salary'] and $data['salary']->save())
				{
					Session::set_flash('success', 'Added New Salary Entry for Employee #'.$employee_id);
					Response::redirect('salaries/view/'.$data['salary']->id);
				}

				else
				{
					Session::set_flash('error', 'Could not save salary.');
				}

		$data['employee'] = Model_Employee::find_by_employee_id($data['salary']->employee_id);
		/*echo $data['employee']->id;
		exit();*/
		$this->template->title = 'Creating new Employee';
		$this->template->content = View::forge('salaries/edit', $data);

 	}

	/*public function action_create()
	{
		$data['salary'] = null;
	 	if (Input::method() == 'POST' or Input::method() == 'GET')
	 	{
	  		$val = Model_Salary::validate('create');

			  	if ($val->run())
			  	{
				   	$data['salary'] = Model_Salary::forge(array(
					   	'employee_id' => Input::post('employee_id'),
					   	'gross' => Input::post('gross'),
					   	'sdxo' => Input::post('sdxo'),
					   	'adj_sdx' => 0,
					   	'pfv' => Input::post('pfv'),
					   	'pf_adj' => 0,
					   	'basic' => 0,
					   	'hra' => 0,
					   	'lta' => 0,
					   	'medical' => Input::post('medical'),
					   	'travel' => Input::post('travel'),
					   	'pf' => 0,
					   	'other1' => 0,
					   	'leave' => Input::post('leave'),
					   	'bonus' => Input::post('bonus'),
					   	'misc1' => Input::post('misc1'),
					   	'misc2' => Input::post('misc2'),
					   	'total1' => 0,
					   	'prof_tax' => Input::post('prof_tax'), 
					   	'inc_tax' => Input::post('inc_tax'),
					   	'other2' => Input::post('other2'),
					   	'total2' => 0,
					   	'net' => 0,
					   	'check' => 0,
					   	'pf_match' => 0,
					   	'gross_match' => 0,
			   	));

			   	if ($data['salary'] and $data['salary']->save())
			   	{
					Session::set_flash('success', 'Added salary #'.$data['salary']->id.'.');

					Response::redirect('salaries');
				}

			   else
			   {
				Session::set_flash('error', 'Could not save salary.');
			}
   		}
	    else
	  	{
	   		Session::set_flash('error', $val->error());
   		}
  	}

  	echo $data['salary']->employee_id;
  	exit();

  	$emp_id = $data['salary']->employee_id;
	$data['employee'] = Model_Employee::find_by_employee_id($emp_id);

	$this->template->title = "Salaries";
	$this->template->content = View::forge('salaries/create', $data);

 }*/

	public function action_createIndividual($employee_id = null)
	{
		is_null($employee_id) and Response::redirect('salaries');

		$employeeList = Model_Employee::find('all');
		$data['employee'] = null;
		$data['salary'] = null;
		foreach ($employeeList as $employeeCurr)
		{
			if ($employeeCurr->employee_id == $employee_id)
			{
				$data['employee'] = $employeeCurr;
				break;
			}
		}
		if (Input::method() == 'POST' or Input::method() == 'GET')
		{
			$val = Model_Salary::validate('create');

			if ($val->run())
			{
				if ($employee->state == 'Karnataka')
				{
					$data['salary'] = Model_Salary::forge(array(
						'employee_id' => Input::post('employee_id'),
						'gross' => Input::post('gross'),
						'sdxo' => Input::post('sdxo'),
						'adj_sdx' => 0,
						'pfv' => Input::post('pfv'),
						'pf_adj' => 0,
						'basic' => 0,
						'hra' => 0,
						'lta' => 0,
						'medical' => Input::post('medical'),
						'travel' => Input::post('travel'),
						'pf' => 0,
						'other1' => 0,
						'leave' => Input::post('leave'),
						'bonus' => Input::post('bonus'),
						'misc1' => Input::post('misc1'),
						'misc2' => Input::post('misc2'),
						'total1' => 0,
						'prof_tax' => Input::post('prof_tax'), 
						'inc_tax' => Input::post('inc_tax'),
						'other2' => Input::post('other2'),
						'total2' => 0,
						'net' => 0,
						'check' => 0,
						'pf_match' => 0,
						'gross_match' => 0,
					));
				}
				else
				{
					$data['salary'] = Model_Salary::forge(array(
						'employee_id' => Input::post('employee_id'),
						'gross' => Input::post('gross'),
						'sdxo' => Input::post('sdxo'),
						'adj_sdx' => 4,
						'pfv' => Input::post('pfv'),
						'pf_adj' => 0,
						'basic' => 0,
						'hra' => 0,
						'lta' => 0,
						'medical' => Input::post('medical'),
						'travel' => Input::post('travel'),
						'pf' => 0, 
						'other1' => 0,
						'leave' => Input::post('leave'),
						'bonus' => Input::post('bonus'),
						'misc1' => Input::post('misc1'),
						'misc2' => Input::post('misc2'),
						'total1' => 0,
						'prof_tax' => 0, 
						'inc_tax' => Input::post('inc_tax'),
						'other2' => Input::post('other2'),
						'total2' => 0,
						'net' => 0,
						'check' => 0,
						'pf_match' => 0,
						'gross_match' => 0,
					));
				}

				if ($data['salary'] and $data['salary']->save())
				{
					Session::set_flash('success', 'Added salary #'.$data['salary']->id.'.');
					Response::redirect('salaries');
				}

				else
				{
					Session::set_flash('error', 'Could not save salary.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Salaries";
		
			$this->template->content = View::forge('salaries/create', $data);
	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('salaries');

		if ( ! $salary = Model_Salary::find($id))
		{
			Session::set_flash('error', 'Could not find salary #'.$id);
		  	Response::redirect('salaries');
	  	}

		$val = Model_Salary::validate('edit');

		if ($val->run())
		{
			$salary->employee_id = Input::post('employee_id');
		  	$salary->gross = Input::post('gross');
		  	$salary->sdxo = Input::post('sdxo');
		  	$salary->adj_sdx = Input::post('adj_sdx');
		  	$salary->pfv = Input::post('pfv');
		  	$salary->pf_adj = Input::post('pf_adj');
		  	$salary->basic = Input::post('basic');
		  	$salary->hra = Input::post('hra');
		  	$salary->lta = Input::post('lta');
		  	$salary->medical = Input::post('medical');
		  	$salary->medical = Input::post('medical');
		  	$salary->travel = Input::post('travel');
		  	$salary->pf = Input::post('pf');
		  	$salary->other1 = Input::post('other1');
		  	$salary->leave = Input::post('leave');
		  	$salary->bonus = Input::post('bonus');
		  	$salary->misc1 = Input::post('misc1');
		  	$salary->misc2 = Input::post('misc2');
		  	$salary->total1 = Input::post('total1');
		  	$salary->prof_tax = Input::post('prof_tax');
		  	$salary->inc_tax = Input::post('inc_tax');
		  	$salary->other2 = Input::post('other2');
		  	$salary->total2 = Input::post('total2');
		  	$salary->net = Input::post('net');
		  	$salary->check = Input::post('check');
		  	$salary->pf_match = Input::post('pf_match');
		  	$salary->gross_match = Input::post('gross_match');

		  	if ($salary->save())
		  	{
		   		Session::set_flash('success', 'Updated salary #' . $id);

		   		Response::redirect('salaries');
	   		}

		  	else
		  	{
		   		Session::set_flash('error', 'Could not update salary #' . $id);
	   		}
	  	}

		else
		{
			if (Input::method() == 'POST')
		  	{
		   		$salary->employee_id = $val->validated('employee_id');
		   		$salary->gross = $val->validated('gross');
		   		$salary->sdxo = $val->validated('sdxo');
		   		$salary->adj_sdx = $val->validated('adj_sdx');
		   		$salary->pfv = $val->validated('pfv');
		   		$salary->pf_adj = $val->validated('pf_adj');
		   		$salary->basic = $val->validated('basic');
		   		$salary->hra = $val->validated('hra');
		   		$salary->lta = $val->validated('lta');
		   		$salary->medical = $val->validated('medical');
		   		$salary->medical = $val->validated('medical');
		   		$salary->travel = $val->validated('travel');
		   		$salary->pf = $val->validated('pf');
		   		$salary->other1 = $val->validated('other1');
		   		$salary->leave = $val->validated('leave');
		   		$salary->bonus = $val->validated('bonus');
		   		$salary->misc1 = $val->validated('misc1');
		   		$salary->misc2 = $val->validated('misc2');
		   		$salary->total1 = $val->validated('total1');
		   		$salary->prof_tax = $val->validated('prof_tax');
		   		$salary->inc_tax = $val->validated('inc_tax');
		   		$salary->other2 = $val->validated('other2');
		   		$salary->total2 = $val->validated('total2');
		   		$salary->net = $val->validated('net');
		   		$salary->check = $val->validated('check');
		   		$salary->pf_match = $val->validated('pf_match');
		   		$salary->gross_match = $val->validated('gross_match');

		   		Session::set_flash('error', $val->error());
	   		}

	   		$employee = Model_Employee::find_by_employee_id($salary->employee_id);

		  	$this->template->set_global('salary', $salary, false);
		  	$this->template->set_global('employee', $employee, false);
	  	}

		$this->template->title = "Salaries";
		$this->template->content = View::forge('salaries/edit');

	}

	public function action_delete($id = null)
	{
	 is_null($id) and Response::redirect('salaries');

	 if ($salary = Model_Salary::find($id))
	 {
	  $salary->delete();

	  Session::set_flash('success', 'Deleted salary #'.$id);
  }

	 else
	 {
	  Session::set_flash('error', 'Could not delete salary #'.$id);
  }

	 Response::redirect('salaries');

 }
 
	public function calculatePayroll($id)
	{
		is_null($id) and Response::redirect('salaries');
		
		if( ! $salary = Model_Salary::find($id) )
		{
			Session::set_flash('error', 'Salary entry not found for calculating payroll');
			Response::redirect('salaries');
		}
		
		$salaryconstants = Model_Salaryconstant::find('first');
		
		/* begin calculations */


		/* change positive SDXO values to negative, if any */
		if ($salary->sdxo > 0)
		{
			$salary->sdxo = -($salary->sdxo);
		}


		/* calculate ADj SDX
		* Adj. SDX = Gross + SDXO
		*/
		$salary->adj_sdx = $salary->gross + $salary->sdxo;


		/* calculate PF Adj.
		* if PFV == 0 then PF Adj = Adj SDX
		* else PF Adj = (Adj SDX) / (PF Adj-constant)
		*/
		if ($salary->pfv == 0)
		{
			$salary->pf_adj = $salary->adj_sdx;
		}
		else
		{
			$salary->pf_adj = ($salary->adj_sdx) / ($salaryconstants->pf_adj);
		}


		/* calculate Basic
		* Basic = Basic-constant * PF Adj
		*/
		$salary->basic = $salaryconstants->basic * $salary->pf_adj;

		/* calculate HRA
		* HRA = HRA-constant * PF Adj
		*/
		$salary->hra = $salaryconstants->hra * $salary->pf_adj;


		/* calculate LTA
		* LTA = LTA-constant * Adj SDX
		*/
		$salary->lta = $salaryconstants->lta * $salary->adj_sdx;


		/* calculate PF
		* if PFV == 0 then PF = 0
		* else PF = PF-constant * Basic
		*/
		if ($salary->pfv == 0)
		{
			$salary->pf = 0;
		}
		else
		{
			$salary->pf = $salaryconstants->pf * $salary->basic;
		}


		/* calculate other1
		* other1 = (PF Adj) - (Basic + HRA + LTA + Medical + Travel + PF)
		*/
		$salary->other1 = $salary->pf_adj - ($salary->basic + $salary->hra + $salary->lta + $salary->medical + $salary->travel + $salary->pf);


		/* calculate total1
		* total1 = (Basic)
		*/
		$salary->total1 = $salary->basic + $salary->hra + $salary->lta + $salary->medical + $salary->travel + $salary->pf + $salary->other1 + $salary->leave + $salary->bonus + $salary->misc1 + $salary->misc2;


		/* calculate total2
		* total2 = Prof Tax + PF + Inc Tax + other2
		*/
		$salary->total2 = $salary->prof_tax + $salary->pf + $salary->inc_tax + $salary->other2;


		/* calculate Net
		* Net = total1 - total2
		*/
		$salary->net = $salary->total1 - $salary->total2;


		/* calculate Check
		* check = Net + total2
		*/
		$salary->check = $salary->net + $salary->total2;


		/* calculate PF Match
		* PF Check = PF
		*/
		$salary->pf_match = $salary->pf;


		/* calcilate Gross Match
		* Gross Match = Check + PF Match
		*/
		$salary->gross_match = $salary->check + $salary->pf_match;


		$salary->save();
	}
	
	public function calculateFytd($id)
	{
		is_null($id) and Response::redirect('salaries');
		
		if( ! $salary = Model_Salary::find($id) )
		{
			Session::set_flash('error', 'Salary entry not found for calculating payroll');
			Response::redirect('salaries');
		}
		
		//echo $salary->month; exit();
		
		$fytd = null;
		
		$fytdList = Model_Fytd::find('all');
		
		
		if ($fytdList != null)
		{
			foreach($fytdList as $fytdTemp)
			{
				$i = $fytdTemp->employee_id;
				$m = $fytdTemp->month;
				$y = $fytdTemp->year; 
				//exit();
				
				if ($i == $salary->employee_id and $m == $salary->month and $y == $salary->year)
				{
					$fytd = $fytdTemp;
					break;
				}
			}
		}
		
		if ($fytd == null)
		{
			$fytd = Model_Fytd::forge(array(
				'employee_id' => $salary->employee_id,
				'month' => $salary->month,
				'year' => $salary->year,
				'basic' => 0,
				'hra' => 0,
				'travel' => 0,
				'medical' => 0,
				'pf' => 0,
				'leave' => 0,
				'other1' => 0,
				'bonus' => 0,
				'misc1' => 0,
				'misc2' => 0,
				'total1' => 0,
				'prof_tax' => 0,
				'inc_tax' => 0,
				'other2' => 0,
				'other3' => 0,
				'other4' => 0,
				'other5' => 0,
				'total2' => 0,
				'net' => 0
			));
			
		}
		
		else
		{
			$fytd->basic = 0;
			$fytd->hra = 0;
			$fytd->travel = 0;
			$fytd->medical = 0;
			$fytd->pf = 0;
			$fytd->leave = 0;
			$fytd->other1 = 0;
			$fytd->bonus = 0;
			$fytd->misc1 = 0;
			$fytd->misc2 = 0;
			$fytd->total1 = 0;
			$fytd->prof_tax = 0;
			$fytd->inc_tax = 0;
			$fytd->other2 = 0;
			$fytd->other3 = 0;
			$fytd->other4 = 0;
			$fytd->other5 = 0;
			$fytd->total2 = 0;
			$fytd->net = 0;
		}
		
		$fytd->save();
		
		if  ($salary->month > 3)
		{
			for ($i = 4; $i <= $salary->month; $i++)
			{
				$salaryList = Model_Salary::find('all');
				foreach ($salaryList as $salaryTemp)
				{
					if ($salaryTemp->employee_id == $salary->employee_id and $salaryTemp->month == $i and $salaryTemp->year == $salary->year)
					{
						$fytd->basic += $salaryTemp->basic;
						$fytd->hra += $salaryTemp->hra;
						$fytd->travel += $salaryTemp->travel;
						$fytd->medical += $salaryTemp->medical;
						$fytd->pf += $salaryTemp->pf;
						$fytd->leave += $salaryTemp->leave;
						$fytd->other1 += $salaryTemp->other1;
						$fytd->bonus += $salaryTemp->bonus;
						$fytd->misc1 += $salaryTemp->misc1;
						$fytd->misc2 += $salaryTemp->misc2;
						$fytd->total1 += $salaryTemp->total1;
						$fytd->prof_tax += $salaryTemp->prof_tax;
						$fytd->inc_tax += $salaryTemp->inc_tax;
						$fytd->other2 += $salaryTemp->other2;
						$fytd->other3 += $salaryTemp->other3;
						$fytd->other4 += $salaryTemp->other4;
						$fytd->other5 += $salaryTemp->other5;
						$fytd->total2 += $salaryTemp->total2;
						$fytd->net += $salaryTemp->net;
					}
				}
			}
		}
		
		else
		{
			for ($i = 4; $i <= 12; $i++)
			{
				$salaryList = Model_Salary::find('all');
				foreach ($salaryList as $salaryTemp)
				{
					if ($salaryTemp->employee_id == $salary->employee_id and $salaryTemp->month == $i and $salaryTemp->year == ($salary->year - 1))
					{
						$fytd->basic += $salaryTemp->basic;
						$fytd->hra += $salaryTemp->hra;
						$fytd->travel += $salaryTemp->travel;
						$fytd->medical += $salaryTemp->medical;
						$fytd->pf += $salaryTemp->pf;
						$fytd->leave += $salaryTemp->leave;
						$fytd->other1 += $salaryTemp->other1;
						$fytd->bonus += $salaryTemp->bonus;
						$fytd->misc1 += $salaryTemp->misc1;
						$fytd->misc2 += $salaryTemp->misc2;
						$fytd->total1 += $salaryTemp->total1;
						$fytd->prof_tax += $salaryTemp->prof_tax;
						$fytd->inc_tax += $salaryTemp->inc_tax;
						$fytd->other2 += $salaryTemp->other2;
						$fytd->other3 += $salaryTemp->other3;
						$fytd->other4 += $salaryTemp->other4;
						$fytd->other5 += $salaryTemp->other5;
						$fytd->total2 += $salaryTemp->total2;
						$fytd->net += $salaryTemp->net;
					}
				}
			}
			
			for ($i = 1; $i <= $salary->month; $i++)
			{
				$salaryList = Model_Salary::find('all');
				foreach ($salaryList as $salaryTemp)
				{
					if ($salaryTemp->employee_id == $salary->employee_id and $salaryTemp->month == $i and $salaryTemp->year == $salary->year)
					{
						$fytd->basic += $salaryTemp->basic;
						$fytd->hra += $salaryTemp->hra;
						$fytd->travel += $salaryTemp->travel;
						$fytd->medical += $salaryTemp->medical;
						$fytd->pf += $salaryTemp->pf;
						$fytd->leave += $salaryTemp->leave;
						$fytd->other1 += $salaryTemp->other1;
						$fytd->bonus += $salaryTemp->bonus;
						$fytd->misc1 += $salaryTemp->misc1;
						$fytd->misc2 += $salaryTemp->misc2;
						$fytd->total1 += $salaryTemp->total1;
						$fytd->prof_tax += $salaryTemp->prof_tax;
						$fytd->inc_tax += $salaryTemp->inc_tax;
						$fytd->other2 += $salaryTemp->other2;
						$fytd->other3 += $salaryTemp->other3;
						$fytd->other4 += $salaryTemp->other4;
						$fytd->other5 += $salaryTemp->other5;
						$fytd->total2 += $salaryTemp->total2;
						$fytd->net += $salaryTemp->net;
					}
				}
			}
		}
		
		$fytd->save();
		
	}

	public function action_calculateIndividual($id = null)
	{
		is_null($id) and Response::redirect('salaries');

		if ( ! $salary = Model_Salary::find($id))
		{
			Session::set_flash('error', 'Salary entry not found');
			Response::redirect('salaries');
		}
		
		$this->calculatePayroll($salary->id);
		$this->calculateFytd($salary->id);
		
		$month = [
			'Jan',
			'Feb',
			'Mar',
			'Apr',
			'May',
			'Jun',
			'Jul',
			'Aug',
			'Sep',
			'Oct',
			'Nov',
			'Dec'
		];
		
		$employee = Model_Employee::find_by_employee_id($salary->employee_id);
		
		Session::set_flash('success', 'Payroll calculated for '.$employee->first_name.' '.$employee->last_name.' (ID: '.$salary->employee_id.') for '.$month[$salary->month - 1].'-'.$salary->year);
		Response::redirect('salaries');
	
	}
	
	public function action_calculateAll ()
	{
		$salaryList = Model_Salary::find('all');
		
		foreach($salaryList as $salaryTemp)
		{
			$this->calculatePayroll($salaryTemp->id);
		}
		
		Session::set_flash('success', 'Payroll calculated for all employees');
		
		Response::redirect('salaries');
	}
	
	public function action_emailIndividualPayslip($id)
	{
		is_null($id) and Response::redirect('salaries');

		if ( ! $salary = Model_Salary::find($id))
		{
			Session::set_flash('error', 'Salary entry not found');
			Response::redirect('salaries');
		}
		
		$employee = Model_Employee::find_by_employee_id($salary->employee_id);
	
		$month = [
			'Jan',
			'Feb',
			'Mar',
			'Apr',
			'May',
			'Jun',
			'Jul',
			'Aug',
			'Sep',
			'Oct',
			'Nov',
			'Dec'
		];
		
		$from_id = 'rahulc93@gmail.com';
		$from_name = 'Rahul Chowdhury';
		$to_id = $employee->email;
        $to_name = $employee->first_name.' '.$employee->last_name;
		$subject = 'Salary Statement for '.$employee->first_name.' '.$employee->last_name.' (ID: '.$salary->employee_id.') for '.$month[$salary->month - 1].'-'.$salary->year;
		$body = 'Please find your salary statement for '.$month[$salary->month - 1].'-'.$salary->year.' attached alongwith';
		$attach = $employee->first_name . '_' . $employee->last_name . '_' . $employee->employee_id . '_' . $month[$salary->month - 1] . '_' . $salary->year . '.pdf';
		
		$this->sendMail(
            	$from_id,
            	$from_name,
            	$to_id,
            	$to_name,
            	$subject,
            	$body,
            	$attach
            	);
		
		
		Session::set_flash('success', 'Payslip emailed to '.$employee->first_name.' '.$employee->last_name.' (ID: '.$salary->employee_id.') for '.$month[$salary->month - 1].'-'.$salary->year);
		Response::redirect('salaries');
	}
	
	public function sendMail($from_id, $from_name, $to_id, $to_name, $subject, $body, $attach = '')
	{
		$email = Email::forge();
		$email->pipelining(true);
		$email->from($from_id, $from_name);
		$email->to($to_id, $to_name);
		$email->subject($subject);
		$email->body($body);
		$email->attach(DOCROOT.$attach);

		try
        {
            $email->send();
        }

        catch(\EmailValidationFailedException $e)
        {
            Session::set_flash('error', "Email Validation Error");
            // The validation failed
            $these_failed = $email->get_invalid_addresses();
            foreach ($thease_failed as $failed)
            {
                echo $failed."\n";
            }
           // Response::redirect('employees');
        }
        
        catch(\EmailSendingFailedException $e)
        {
            Session::set_flash('error', "Email Sending Failed\n".$e);
           // Response::redirect('employees');
           // The driver could not send the email
		}
	}
}
