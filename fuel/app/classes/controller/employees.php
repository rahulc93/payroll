<?php

//use Knp\Snappy\Pdf;

//  include ("/var/www/html/mpdf/MPDF57/mpdf.php");

//require_once("dompdf_config.inc.php");


class Controller_Employees extends Controller_Template{

	public function action_index()
	{
		$data['employees'] = Model_Employee::find('all');
		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/index', $data);

	}

	public function action_viewPersonalDetails($id = null)
	{
	 	is_null($id) and Response::redirect('employees');

	 	if ( ! $data['employee'] = Model_Employee::find($id))
	 	{
	  		Session::set_flash('error', 'Could not find employee entry #'.$id);
	  		Response::redirect('employees');
  		}

	 	$this->template->title = 'Personal Details for '.$data['employee']->first_name.' '.$data['employee']->last_name.' (ID: '.$data['employee']->employee_id.')';
	 	$this->template->content = View::forge('employees/viewPersonalDetails', $data);

 	}

 	public function action_viewContactDetails($id = null)
	{
	 	is_null($id) and Response::redirect('employees');

	 	if ( ! $data['employee'] = Model_Employee::find($id))
	 	{
	  		Session::set_flash('error', 'Could not find employee entry #'.$id);
	  		Response::redirect('employees');
  		}

		$this->template->title = 'Contact Details for '.$data['employee']->first_name.' '.$data['employee']->last_name.' (ID: '.$data['employee']->employee_id.')';	 	
		$this->template->content = View::forge('employees/viewContactDetails', $data);

 	}

 	public function action_viewEmergencyContactDetails($id = null)
	{
	 	is_null($id) and Response::redirect('employees');

	 	if ( ! $data['employee'] = Model_Employee::find($id))
	 	{
	  		Session::set_flash('error', 'Could not find employee entry #'.$id);
	  		Response::redirect('employees');
  		}

	 	$this->template->title = 'Emergency Contact Details for '.$data['employee']->first_name.' '.$data['employee']->last_name.' (ID: '.$data['employee']->employee_id.')';
	 	$this->template->content = View::forge('employees/viewEmergencyContactDetails', $data);

 	}

 	public function action_viewJobDetails($id = null)
	{
	 	is_null($id) and Response::redirect('employees');

	 	if ( ! $data['employee'] = Model_Employee::find($id))
	 	{
	  		Session::set_flash('error', 'Could not find employee entry #'.$id);
	  		Response::redirect('employees');
  		}

	 	$this->template->title = 'Job Details for '.$data['employee']->first_name.' '.$data['employee']->last_name.' (ID: '.$data['employee']->employee_id.')';
	 	$this->template->content = View::forge('employees/viewJobDetails', $data);

 	}

 	public function action_addNew()
 	{
 		if ( ! $lastEmployee = Model_Employee::find('last'))
		{
			$emp_id = 1;
		}
		
		else
		{
			$emp_id = $lastEmployee->employee_id + 1;
		}
				
		$data['employee'] = Model_Employee::forge(array(
			'action' => 'create',
			'employee_id' => $emp_id,
			'title' => '',
			'first_name' => '',
			'last_name' => '',
			'sex' => '',
			'date_of_birth' => '',
			'marital_status' => '',

			'branch' => '',
			'joining_date' => '',
			'leaving_date' => '',
			'activity_status' => '',

			'phone' => '',
			'address' => '',
			'address2' => '',
			'address3' => '',
			'state' => '',
			'city' => '',
			'pincode' => '',
			'email' => '',

			'emergency_contact_first_name' => '',
			'emergency_contact_last_name' => '',
			'emergency_contact_address' => '',
			'emergency_contact_state' => '',
			'emergency_contact_city' => '',
			'emergency_contact_pincode' => '',
			'emergency_contact_phone' => '',
			'emergency_contact_email' => '',

			'issaved' => 'false',
		));

		if ($data['employee'] and $data['employee']->save())
		{
			Session::set_flash('success', 'Added New Entry for new employee');
		}

		else
		{
			Session::set_flash('error', 'Could not save employee.');
		}
			
		Response::redirect('employees/edit/'.$data['employee']->id.'/1/1'); 	
	}

	public function action_createPersonalDetails($id)
	{

		/*$data['employee'] = null;
		if (Input::method() == 'POST')
		{
			$val = Model_Employee::validate('create');


			
			if ($val->run())
			{
				$data['employee'] = Model_Employee::forge(array(
					'employee_id' => Input::post('employee_id'),
					'title' => Input::post('title'),
					'first_name' => Input::post('first_name'),
					'last_name' => Input::post('last_name'),
					'sex' => Input::post('sex'),
					'date_of_birth' => Input::post('date_of_birth'),
					'marital_status' => Input::post('marital_status'),
					
					'branch' => 0,
					'joining_date' => 0,
					'leaving_date' => 0,
					'activity_status' => 0,
					
					
					'phone' => 0,
					'address' => 0,
					'state' => 0,
					'city' => 0,
					'pincode' => 0,
					'email' => 0,

					'emergency_contact_first_name' => 0,
					'emergency_contact_last_name' => 0,
					'emergency_contact_address' => 0,
					'emergency_contact_state' => 0,
					'emergency_contact_city' => 0,
					'emergency_contact_pincode' => 0,
					'emergency_contact_phone' => 0,
					'emergency_contact_email' => 0,

					'issaved' => 'false',
					'action' => 'create'
				));

				if ($data['employee'] and $data['employee']->save())
				{
					Session::set_flash('success', 'Added personal details for employee #'.$data['employee']->employee_id.'.');

					Response::redirect('employees/createContactDetails/'.$data['employee']->employee_id);
				}

				else
				{
					Session::set_flash('error', 'Could not save employee.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/createPersonalDetails', $data);*/



		$data['employee'] = Model_Employee::find($id);

		$val = Model_Employee::validate('create');

		if ($val->run())
		{
				$data['employee']->employee_id = Input::post('employee_id');
				$data['employee']->title = Input::post('title');
				$data['employee']->first_name = Input::post('first_name');
				$data['employee']->last_name = Input::post('last_name');
				$data['employee']->sex = Input::post('sex');
				$data['employee']->date_of_birth = Input::post('date_of_birth');
				$data['employee']->marital_status = Input::post('marital_status');
	   
				if ($data['employee'] and $data['employee']->save())
				{
					Session::set_flash('success', 'Added contact information for employee #'.$data['employee']->employee_id.'.');

					Response::redirect('employees/createContactDetails/'.$data['employee']->employee_id);
				}

				else
				{
					Session::set_flash('error', 'Could not save employee.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
			
		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/createPersonalDetails', $data);

	}

	public function action_createContactDetails($employee_id)
	{
		$data['employee'] = Model_Employee::find_by_employee_id($employee_id);

		$val = Model_Employee::validate('create');

		if ($val->run())
		{
				$data['employee']->phone = Input::post('phone');
				$data['employee']->address = Input::post('address');
				$data['employee']->state = Input::post('state');
				$data['employee']->city = Input::post('city');
				$data['employee']->pincode = Input::post('pincode');
				$data['employee']->email = Input::post('email');
	   
				if ($data['employee'] and $data['employee']->save())
				{
					Session::set_flash('success', 'Added contact information for employee #'.$data['employee']->employee_id.'.');

					Response::redirect('employees/createEmergencyContactDetails/'.$data['employee']->employee_id);
				}

				else
				{
					Session::set_flash('error', 'Could not save employee.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
			
		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/createContactDetails', $data);

	}

	public function action_createEmergencyContactDetails($employee_id = null)
	{
		$data['employee'] = Model_Employee::find_by_employee_id($employee_id);

		$val = Model_Employee::validate('create');

		if ($val->run())
		{
				$data['employee']->emergency_contact_first_name = Input::post('emergency_contact_first_name');
				$data['employee']->emergency_contact_last_name = Input::post('emergency_contact_last_name');
				$data['employee']->emergency_contact_address = Input::post('emergency_contact_address');
				$data['employee']->emergency_contact_state = Input::post('emergency_contact_state');
				$data['employee']->emergency_contact_city = Input::post('emergency_contact_city');
				$data['employee']->emergency_contact_pincode = Input::post('emergency_contact_pincode');
				$data['employee']->emergency_contact_phone = Input::post('emergency_contact_phone');
				$data['employee']->emergency_contact_email = Input::post('emergency_contact_email');
	   
				if ($data['employee'] and $data['employee']->save())
				{
					Session::set_flash('success', 'Added emergency contact information for employee #'.$data['employee']->employee_id.'.');

					Response::redirect('employees/createJobDetails/'.$data['employee']->employee_id);
				}

				else
				{
					Session::set_flash('error', 'Could not save employee.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
			
		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/createEmergencyContactDetails', $data);
	}
	
	public function action_createJobDetails ($employee_id = null)
	{
		$data['employee'] = Model_Employee::find_by_employee_id($employee_id);

		$val = Model_Employee::validate ('create');

		if ($val->run())
		{
			$data['employee']->branch = Input::post('branch');
			$data['employee']->joining_date = Input::post('joining_date');
			$data['employee']->leaving_date = Input::post('leaving_date');
			$data['employee']->activity_status = Input::post('activity_status');

			if ($data['employee'] and $data['employee']->save())
			{
				Session::set_flash('success', 'Added Job Details for Employee #'.$data['employee']->employee_id.'.');

				Response::redirect('employees/view/'.$data['employee']->employee_id);
			}

			else
			{
				Session::set_flash('error', 'Could not save employee.');
			}
		}
		else
		{
			Session::set_flash('error', $val->error());
		}
			
		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/createJobDetails', $data);

	}

	public function action_edit($id, $redirect, $act)
	{
		//is_null($id) and Response::redirect('employees');

		if ( ! $employee = Model_Employee::find($id))
		{
			Session::set_flash('error', 'Employee not found'.$id);
			Response::redirect('employees');
		}
		
		if ($act == 0)
		{
			$employee->action = 'edit';
			$employee->issaved = 'false';
		}
		else
		{
			$employee->action = 'create';
		}

		$employee->save();
		
		if ($redirect == 1)
		{
			Response::redirect('employees/editPersonalDetails/'.$id);
		}

		else if ($redirect == 2)
		{
			Response::redirect('employees/editContactDetails/'.$id);
		}

		else if ($redirect == 3)
		{
			Response::redirect('employees/editEmergencyContactDetails/'.$id);
		}

		else if ($redirect == 4)
		{
			Response::redirect('employees/editJobDetails/'.$id);
		}

	}

	public function action_editPersonalDetails($id = null)
	{
		is_null($id) and Response::redirect('employees');

		if ( ! $employee = Model_Employee::find($id))
		{
			Session::set_flash('error', 'Could not find employee entry #'.$id);
			Response::redirect('employees');
		}

		$val = Model_Employee::validate('edit');

		if ($val->run())
		{
			
			$employee->issaved = 'false';

			$employee->employee_id = Input::post('employee_id');
			$employee->title = Input::post('title');
			$employee->first_name = Input::post('first_name');
			$employee->last_name = Input::post('last_name');
			$employee->sex = Input::post('sex');
			$employee->date_of_birth = Input::post('date_of_birth');
			$employee->marital_status = Input::post('marital_status');

			if ($employee->save())
			{
				Session::set_flash('success', 'Updated basic information for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');

				if ($employee->action == 'edit')
				{
					Response::redirect('employees/viewPersonalDetails/'.$id);
				}

				else if ($employee->action == 'create')
				{
					Response::redirect('employees/edit/'.$id.'/2/1');
				}
			}

			else
			{
				Session::set_flash('error', 'Could not update employee entry #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$employee->employee_id = $val->validated('employee_id');
				$employee->title = $val->validated('title');
				$employee->first_name = $val->validated('first_name');
				$employee->last_name = $val->validated('last_name');
				$employee->sex = $val->validated('sex');
				$employee->date_of_birth = $val->validated('date_of_birth');
				$employee->marital_status = $val->validated('marital_status');
				
				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('employee', $employee, false);
		}

		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/editPersonalDetails');

	}
	
	public function action_editContactDetails($id = null)
	{
		is_null($id) and Response::redirect('employees');

		if ( ! $employee = Model_Employee::find($id))
		{
			Session::set_flash('error', 'Could not find employee entry #'.$id);
			Response::redirect('employees');
		}

		$val = Model_Employee::validate('edit');

		if ($val->run())
		{
			
			$employee->phone = Input::post('phone');
			$employee->address = Input::post('address');
			$employee->state = Input::post('state');
			$employee->city = Input::post('city');
			$employee->pincode = Input::post('pincode');
			$employee->email = Input::post('email');

			if ($employee->save())
			{
				Session::set_flash('success', 'Updated contact information for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');

				if ($employee->action == 'edit')
				{
					Response::redirect('employees/viewContactDetails/'.$id);
				}

				else if ($employee->action == 'create')
				{
					Response::redirect('employees/edit/'.$id.'/3/1');
				}
			}

			else
			{
				Session::set_flash('error', 'Could not update employee entry #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$employee->phone = $val->validated('phone');
				$employee->address = $val->validated('address');
				$employee->state = $val->validated('state');
				$employee->city = $val->validated('city');
				$employee->pincode = $val->validated('pincode');
				$employee->email = $val->validated('email');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('employee', $employee, false);
		}

		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/editContactDetails');

	}

	public function action_editEmergencyContactDetails ($id = null)
	{
		is_null($id) and Response::redirect('employees');

		if ( ! $employee = Model_Employee::find($id))
		{
			Session::set_flash('error', 'Could not find employee entry #'.$id);
			Response::redirect('employees');
		}

		$val = Model_Employee::validate('edit');

		if ($val->run())
		{
			$employee->emergency_contact_first_name = Input::post('emergency_contact_first_name');
			$employee->emergency_contact_last_name = Input::post('emergency_contact_last_name');
			$employee->emergency_contact_address = Input::post('emergency_contact_address');
			$employee->emergency_contact_state = Input::post('emergency_contact_state');
			$employee->emergency_contact_city = Input::post('emergency_contact_city');
			$employee->emergency_contact_pincode = Input::post('emergency_contact_pincode');
			$employee->emergency_contact_phone = Input::post('emergency_contact_phone');
			$employee->emergency_contact_email = Input::post('emergency_contact_email');

			if ($employee->save())
			{
				Session::set_flash('success', 'Updated emergency contact information for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');

				if ($employee->action == 'edit')
				{
					Response::redirect('employees/viewEmergencyContactDetails/'.$id);
				}

				else if ($employee->action == 'create')
				{
					Response::redirect('employees/edit/'.$id.'/4/1');
				}
			}

			else
			{
				Session::set_flash('error', 'Could not update employee entry #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$employee->emergency_contact_first_name = $val->validated('emergency_contact_first_name');
				$employee->emergency_contact_last_name = $val->validated('emergency_contact_last_name');
				$employee->emergency_contact_address = $val->validated('emergency_contact_address');
				$employee->emergency_contact_state = $val->validated('emergency_contact_state');
				$employee->emergency_contact_city = $val->validated('emergency_contact_city');
				$employee->emergency_contact_pincode = $val->validated('emergency_contact_pincode');
				$employee->emergency_contact_phone = $val->validated('emergency_contact_phone');
				$employee->emergency_contact_email = $val->validated('emergency_contact_email');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('employee', $employee, false);
		}

		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/editEmergencyContactDetails');
	}

	public function action_editJobDetails ($id = null)
	{
		is_null($id) and Response::redirect('employees');

		if ( ! $employee = Model_Employee::find($id))
		{
			Session::set_flash('error', 'Could not find employee entry #'.$id);
			Response::redirect('employees');
		}

			$val = Model_Employee::validate('edit');

			if ($val->run())
			{
				$employee->branch = Input::post('branch');
				$employee->joining_date = Input::post('joining_date');
				$employee->leaving_date = Input::post('leaving_date');
				$employee->date_of_birth = Input::post('date_of_birth');
				$employee->activity_status = Input::post('activity_status');

				if ($employee->save())
				{
					Session::set_flash('success', 'Updated Job information for ' . $employee->first_name.' '.$employee->last_name.' (ID: '.$employee->employee_id.' )');

					if ($employee->action == 'edit')
					{
						Response::redirect('employees/viewEmergencyContactDetails/'.$id);
					}

					else if ($employee->action == 'create')
					{
						Response::redirect('banks/addNew/'.$employee->employee_id);
					}
				}

			else
			{
				Session::set_flash('error', 'Could not update employee entry #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$employee->branch = $val->validated('branch');
				$employee->joining_date = $val->validated('joining_date');
				$employee->leaving_date = $val->validated('leaving_date');
				$employee->date_of_birth = $val->validated('date_of_birth');
				$employee->activity_status = $val->validated('activity_status');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('employee', $employee, false);
		}

		$this->template->title = "Employees";
		$this->template->content = View::forge('employees/editJobDetails');

	}

	public function action_delete($employee_id = null)
	{
		is_null($employee_id) and Response::redirect('employees');

		if ($employee = Model_Employee::find_by_employee_id($employee_id))
		{
			$employee->delete();

			Session::set_flash('success', 'Deleted employee #'.$employee_id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete employee #'.$employee_id);
		}

		Response::redirect('employees');

	}

	public function action_showSaveMessage ($employee_id = null)
	{
		is_null($employee_id) and Response::redirect('employees');
		Session::set_flash('success', 'Saved employee #'.$employee_id);
		Model_Employee::find_by_employee_id($employee_id)->issaved = 'true';
		Model_Employee::find_by_employee_id($employee_id)->save();
		Response::redirect('employees');
	}

	public function action_createPDF ($employee_id = null)
	{
           
            
            //$this->template->title = '';
            //$this->template->content = View::forge('employees/viewPersonalDetails', $data);
			$html = '<p>Hello World!</p>';  
            $pdf = \Pdf\Pdf::forge('dompdf')->init();
            $pdf->load_html($html);
            $pdf->render(true);
            $pdf->stream('sample.pdf');
            Session::set_flash('success', 'PDF Generated');
            Response::redirect('employees');
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

	public static function action_testSendMail()
        {
            $from_id = 'rahulc93@gmail.com';
            $from_name = 'Rahul Chowdhury';
            $to_id = 'subhajitm6@gmail.com';
            $to_name = 'Subhajit Mukherjee';
            $subject = 'Testing Mail';
            $body = 'This is a sample mail sending app';
            $attach = '2014_2013_HD_2013_11.pdf';
            $this->sendMail(
            	$from_id,
            	$from_name,
            	$to_id,
            	$to_name,
            	$subject,
            	$body,
            	$attach
            	);
            Session::set_flash('success', 'Email Successfully Sent!');
            Response::redirect('employees');
        }
	
}
