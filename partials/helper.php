<?php
/* Helper
*
* Helper class
*/
class Helper {    

    private $json_file = './dataset/users.json';

    public function getData() {
        $content = file_get_contents($this->json_file, true);
        $file_content = json_decode($content, true);
        if(!empty($file_content)) {
            return $file_content;
        }
        return false;
    }

    public function insertToFile($data) {
      return file_put_contents($this->json_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function deleteRowById($id) {
        $file_content = $this->getData();
        $newData = array_filter($file_content, function ($var) use ($id) { 
            return ($var['id'] != $id); 
        });

        return $this->insertToFile($newData);
    }

    public function addUser($user) {
        if(!empty($user)) {
            $file_content = $this->getData();
            $id = intval(max(array_column($file_content, 'id'))) + 1;
            $user = array(
                'id' => $id,
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'address' => array(
                    'street' => $user['street'],
                    'city' => $user['city'],
                    'zipcode' => $user['zipcode']
                ),
                'phone' => $user['email'],
                'company' => array(
                    'name' => $user['company']
                )
            );
            $file_content = !empty($file_content)?array_filter($file_content ):$file_content;

            if(!empty($file_content)){ 
                array_push($file_content, $user); 
            } else { 
                $file_content[] = $user; 
            }

            return $this->insertToFile($file_content);
        } else {
            return false;
        }
    }

    public function addForm() {

        $html = '
        <div class="mx-auto w-25 mb-4">
        <h3>Add new user</h3>
        <form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
        <input type="hidden" id="enteruser" name="adduser" value="true" />
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
        </div>        
        <div class="form-group">
          <label for="street">Street</label>
          <input type="text" class="form-control" id="street" placeholder="Enter street" name="street">
        </div> 
        <div class="form-group">
          <label for="zipcode">Zip-code</label>
          <input type="text" class="form-control" id="zipcode" placeholder="Enter zipcode" name="zipcode">
        </div>        
        <div class="form-group">
          <label for="city">City</label>
          <input type="text" class="form-control" id="city" placeholder="Enter city" name="city">
        </div>      
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone">
        </div>
        <div class="form-group">
          <label for="company">Company</label>
          <input type="text" class="form-control" id="company" placeholder="Enter company name" name="company">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>';

      return $html;

    }

    public function printTable($data) {

        $html = '<table class="table mx-auto overflow-auto table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Address</th>
            <th scope="col">Phone</th>
            <th scope="col">Company</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>';

        if($data) {        
            foreach($data as $user) {

                $name = isset($user['name'])?$user['name']:'';
                $username = isset($user['username'])?$user['username']:'';
                $email = isset($user['email'])?$user['email']:'';
                $phone = isset($user['phone'])?$user['phone']:'';
                $street = isset($user['address']['street'])?$user['address']['street']:'';
                $zipcode = isset($user['address']['zipcode'])?$user['address']['zipcode']:'';
                $city = isset($user['address']['city'])?$user['address']['city']:'';
                $company = isset($user['company']['name'])?$user['company']['name']:'';
                
                $html .= ('
                <tr>
                    <td>'. $name.'</td>
                    <td>'. $username.'</td>
                    <td>'. $email.'</td>
                    <td>'. $street.', '. $zipcode.' '. $city.'</td>
                    <td>'. $phone.'</td>
                    <td>'. $company.'</td>
                    <td>
                        <form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
                        <input type="hidden" id="postId" name="id" value="'. $user['id'] . '" />
                        <button class="btn btn-danger"><i class="fa fa-trash" type="submit"></i> Remove</button></form>
                    </td>
                </tr>');
            }
        } else {
            $html .= '<td colspan="7">Users not found</td>';
        }
        $html .= '
        </tbody>
        </table>';

        return $html;
    }



    
}