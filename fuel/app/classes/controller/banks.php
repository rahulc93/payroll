<?php
class Controller_Banks extends Controller_Template{

	public function action_index()
	{
		$data['banks'] = Model_Bank::find('all');
		$this->template->title = "Banks";
		$this->template->content = View::forge('banks/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('banks');

		if ( ! $data['banks'] = Model_Bank::find($id))
		{
			Session::set_flash('error', 'Could not find Bank Entry #'.$id);
			Response::redirect('employees');
		}
		
		$data['employee'] = Model_Employee::find_by_employee_id($data['banks']->employee_id);

		$this->template->title = 'Bank Details for '.$data['employee']->first_name.' '.$data['employee']->last_name.' (ID: '.$data['employee']->employee_id.')';
		$this->template->content = View::forge('banks/view', $data);

	}
	
	public function action_addNew($employee_id = null)
	{
		is_null($employee_id) and Response::redirect('employees');
		
		if ( ! $employee = Model_Employee::find_by_employee_id($employee_id))
		{
			Session::set_flash('error', 'Could not find employee #'.$employee_id);
			Response::redirect('employees');
		}
		
		$bank = Model_Bank::forge(array(
			'employee_id' => $employee_id,
			'account_number' => '',
			'account_type' => '',
			'branch' => '',
			'address' => '',
			'state' => '',
			'city' => '',
			'ifsc_code' => ''
		));
		
		if ($bank and $bank->save())
		{
			Session::set_flash('success', 'Added bank details for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');
			Response::redirect('banks/edit/'.$employee_id);
		}

		else
		{
			Session::set_flash('error', 'Could not save bank details for employee #'.$employee_id);
		}
	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{

			//$data['bank'] = Model_Bank::find_by_employee_id($employee_id);
			
			$val = Model_Bank::validate('create');
			

			if ($val->run())
			{
				$bank = Model_Bank::forge(array(
					'employee_id' => Input::post('employee_id'),
					'account_number' => Input::post('account_number'),
					'account_type' => Input::post('account_type'),
					'branch' => Input::post('branch'),
					'address' => Input::post('address'),
					'state' => Input::post('state'),
					'city' => Input::post('city'),
					'ifsc_code' => Input::post('ifsc_code')
				));

				if ($bank and $bank->save())
				{
					Session::set_flash('success', 'Added bank details for employee #'.$employee_id.'.');
					Response::redirect('banks/view/'.$id);
				}

				else
				{
					Session::set_flash('error', 'Could not save bank details for employee #'.$employee_id);
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}

		}
		$this->template->title = "Banks";
		$this->template->content = View::forge('banks/create');

	}

	public function action_edit($employee_id = null)
	{
		is_null($employee_id) and Response::redirect('employees');

		if ( ! $bank = Model_Bank::find_by_employee_id($employee_id))
		{
			Session::set_flash('error', 'Could not find bank entry for employee #'.$employee_id);
			Response::redirect('banks');
		}
		
		$employee = Model_Employee::find_by_employee_id($employee_id);

		$val = Model_Bank::validate('edit');

		if ($val->run())
		{
			$bank->employee_id = Input::post('employee_id');
			$bank->account_number = Input::post('account_number');
			$bank->account_type = Input::post('account_type');
			$bank->branch = Input::post('branch');
			$bank->address = Input::post('address');
			$bank->state = Input::post('state');
			$bank->city = Input::post('city');
			$bank->ifsc_code = Input::post('ifsc_code');

			if ($bank->save())
			{
				Session::set_flash('success', 'Updated Bank Details for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');

				Response::redirect('banks/view/'.$bank->id);
			}

			else
			{
				Session::set_flash('error', 'Could not update bank details for Employee #' . $employee_id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				//$bank->employee_id = $val->validated('employee_id');
				$bank->account_number = $val->validated('account_number');
				$bank->account_type = $val->validated('account_type');
				$bank->branch = $val->validated('branch');
				$bank->address = $val->validated('address');
				$bank->state = $val->validated('state');
				$bank->city = $val->validated('city');
				$bank->ifsc_code = $val->validated('ifsc_code');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('banks', $bank, false);
		}

		$this->template->title = "Banks";
		$this->template->content = View::forge('banks/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('banks');

		if ($bank = Model_Bank::find($id))
		{
			$employee = Model_Employee::find_by_employee_id($bank->employee_id);
			
			$bank->delete();

			Session::set_flash('success', 'Deleted Bank Details for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');
		}

		else
		{
			Session::set_flash('error', 'Could not delete Bank Entry #'.$id);
		}

		Response::redirect('banks');

	}


}
