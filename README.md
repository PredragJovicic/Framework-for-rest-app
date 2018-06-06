# Framework-for-rest-app

## Easy framework for making Rest applications

### Setting config.php file
Open config.php
```
example

'url' => "http://YourUrl"
'apikey' => "Your Api Key"

'database' => "Your Database Name"
'host' => "Host Name"
'username' => "User Name"
'password' => "Password"
```	

### Writing Routes
Open routes.php
GET,POST,PUT,DELETE are supported server methods
Route::[server method]([route], [controller]@[controller method]);
```
example

Route::post('/login', 'UserController@Login');
Route::get('/logout/{access_token}', 'UserController@Logout');
Route::post('/register', 'UserController@Register');

Route::get('/', 'UserController@home');
Route::get('/users/', 'UserController@index');
Route::get('/users/{access_token}', 'UserController@show');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@delete');

Route::post('/search', 'UserController@search');
Route::get('/search/{search}/{offset}/{limit}', 'UserController@search');
```
### Request, response and data types

Files must be in binary type or encoded with base_64

All responses are in json type   
```
{"response":"Some data"}
```
All requests must be json type
Getting data from request

If method have body. Key name is body
```
array $data = $request['body'];
```
If method have no body.Key name must be same as it is  define in route
```
$data = $request['valiable define in route'];
```
Example PUT method
```
Route::put('/users/{id}', 'UserController@update');
{"name":"My Name","email":"you@you.com"} - data sent in body of request

echo $request['id'];  //retult id value
echo $request['body']['name'];  //retult My Name
echo $request['body']['email'];  //retult you@you.com
```

### Create controller
Open folder controllers and save your file here. File name and class name must be same
```
example

Save it as UserController.php
class UserController extends Controller
{

	public static function home()
	{	
		return "home";
	}

}
```

### Create model
Open folder models and save your file here. File name, class name and name of database table must be same.
```
example for model named User 

Save it as User.php.
Create table in your database and name it User

class User extends Model
{
	public static function DoSomeThing()
	{	
		return "Something about user";
	}
}
```

### Create helper
Open folder helpers and save your file here. File name and class name must be same.
Here ypu can write your classes or functions.
```
example

Save it as MyHelper.php
class MyHelper
{

	public static function FirstHelp()
	{	
		return "First Help";
	}

}
```

### Calling methods
Just call method normaly, everything is incuded from folders controllers, models, helpers
[controler]::[controler method]
```
example

UserController::home();
User::DoSomeThing();
MyHelper::FirstHelp();

```
### Smart coding method
Smart coding method allow you to do your work much faster.
You can insert, update, delete and search data from database very easy.

Smart coding method for controllers
```
example

Route::post('/login', 'UserController@Login');
Route::get('/logout/{access_token}', 'UserController@Logout');
Route::post('/register', 'UserController@Register');
```
login needs with email and password, and generate access_token
logout needs access_token to logout user
Register encrypt password automacly if key is json string is named 'password' and upload file in files folder. 
Name of files must start with 'file_'. 

Smart coding method for models
```
example

Route::get('/users/', 'UserController@index');
User::All(); // get all users from table user

Route::get('/users/{access_token}', 'UserController@show');
User::Select($request); // get specific user from table user

Route::post('/users', 'UserController@store');
User::Insert($request); // Insert user in table user, encrypt password automacly if key in json
						// string is named 'password'. Upload file in files folder automacly if 
						// name of key start with 'file_'.

Route::put('/users/{id}', 'UserController@update');	   
User::Update($request);	// update specific user in table user

Route::delete('/users/{id}', 'UserController@delete');
User::Delete($request); // delete specific user in table user

Route::post('/search', 'UserController@search');
{"search":"Some to search","offset" : 0,"limit" : 30}
Route::get('/search/{search}/{offset}/{limit}', 'UserController@search');
$ColumnsToSearch = ['name','email'];
$OrderBy = ['id','desc'];
User::Search($request,$ColumnsToSearch,$OrderBy); // Search table user. Search requests can be POST or GET
			// Searh must have this keys {"search":"","offset" : "","limit" : ""}

User::CheckAccessToken(); // check if User has access_token. If not exit program with error message
```

