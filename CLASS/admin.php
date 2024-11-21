<?php

class appControl
{

    private $db;

    public function __construct()
    {
        $host_name = 'localhost';
        $user_name = 'root';
        $password = '';
        $db_name = 'ecar';

        try {
            $connection = new PDO("mysql:host={$host_name};dbname={$db_name}", $user_name,  $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $connection; // connection established
        } catch (PDOException $exception) {
            // You might want to log the error or handle it differently
            throw new Exception("Database Connection Error: " . $exception->getMessage());
        }
    }

    /* ---------------------- test_form_input_data ----------------------------------- */

    public function test_form_input_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    /* ---------------------- Admin Login Check ----------------------------------- */

    public function admin_login_check($data)
    {

        try {
            $upass = $this->test_form_input_data(md5($data['admin_password']));
            $username = $this->test_form_input_data($data['username']);
            $login_type = $this->test_form_input_data($data['login_type']);

            // Existing tables
            $table1 = 'accounts';
            $table2 = 'vendors';
            $table3 = 'suppliers';

            if ($login_type === 'euro_float') {
                $table = $table1;
                $table_id = 'user_id';
                $user_role = 'user_role';
                $location = 'Dashboard.php';
            } elseif ($login_type === 'vendor_login') {
                $table = $table2;
                $table_id = 'vendor_id';
                $user_role = 'account_type';
                $location = 'vendorsDashboard.php';
            } else {
                $table = $table3;
                $table_id = 'supplier_id';
                $user_role = 'type_role';
                $location = 'supplierDashboard.php';
            }

            $stmt = $this->db->prepare("SELECT * FROM $table WHERE username=:uname AND password=:upass LIMIT 1 ");
            $stmt->execute(array(':uname' => $username, ':upass' => $upass));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                session_start();

                $_SESSION['admin_id'] = $userRow[$table_id];
                $_SESSION['name'] = $userRow['fullname'];
                $_SESSION['security_key'] = 'rewsgf@%^&*nmghjjkh';
                $_SESSION['user_role'] = $userRow[$user_role];



                if ($_SESSION['OTP'] == 1234) {

                    header('Location: ../' . $location);
                } else {
                    header('Location: ./mfaMail.php?MFA='.$table);
                }

            } else {
                $message = 'Invalid user name or Password';
                return $message;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /* -------------------- Admin Logout ----------------------------------- */

    public function admin_logout()
    {

        session_start();


        if ($_SESSION['user_role'] == 5) {
            unset($_SESSION['admin_id']);
            unset($_SESSION['admin_name']);
            unset($_SESSION['security_key']);
            unset($_SESSION['user_role']);
            header('Location: ../index.php');
        } else {
            unset($_SESSION['admin_id']);
            unset($_SESSION['admin_name']);
            unset($_SESSION['security_key']);
            unset($_SESSION['user_role']);
            unset($_SESSION['verify_access']);
            header('Location: index.php');
        }
        session_destroy();

    }

    /* ----------------------manage_all_info--------------------- */

    public function manage_all_info($sql)
    {
        try {
            $info = $this->db->prepare($sql);
            $info->execute();
            return $info;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }



	/*----------- add_new_user--------------*/

	public function add_new_user($data)
	{
		$user_fullname  = $this->test_form_input_data($data['em_fullname']);
		$user_username = $this->test_form_input_data($data['em_email']);
		$user_email = $this->test_form_input_data($data['em_email']);
        $em_password = $this->test_form_input_data($data['em_password']);
        $phone_adduser = $this->test_form_input_data($data['phone']);
        $em_user_role = $this->test_form_input_data($data['em_user_role']);
		
		try {
			$sqlEmail = "SELECT email_id FROM accounts WHERE email_id = '$user_email' ";
			$query_result_for_email = $this->manage_all_info($sqlEmail);
			$total_email = $query_result_for_email->rowCount();

			$sqlUsername = "SELECT username FROM accounts WHERE username = '$user_username' ";
			$query_result_for_username = $this->manage_all_info($sqlUsername);
			$total_username = $query_result_for_username->rowCount();

			if ($total_email != 0 && $total_username != 0) {
				$message = "Email and Password both are already taken";
				return $message;
			} elseif ($total_username != 0) {
				$message = "Username Already Taken";
				return $message;
			} elseif ($total_email != 0) {
				$message = "Email Already Taken";
				return $message;
			} else {
				$add_user = $this->db->prepare("INSERT INTO accounts (username, password, user_role, fullname, email_id, phone_no) VALUES (:x, :y, :z, :a, :b, :c) ");

				$add_user->bindparam(':x', $user_username);
				$add_user->bindparam(':y', $em_password);
				$add_user->bindparam(':z', $em_user_role);
				$add_user->bindparam(':a', $user_fullname);
				$add_user->bindparam(':b', $user_email);
				$add_user->bindparam(':c', $phone_adduser);

				$add_user->execute();
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


    /* --------------------delete_data_by_this_method--------------*/

    public function delete_data_by_this_method($sql, $action_id, $sent_po)
    {
        try {
            $delete_data = $this->db->prepare($sql);

            $delete_data->bindparam(':id', $action_id);

            $delete_data->execute();

            @header('Location: ' . $sent_po);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


}