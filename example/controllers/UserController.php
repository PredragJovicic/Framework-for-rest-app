<?php

class UserController extends Controller
{

	public static function base()
	{
		Log::info('info message', "HIGH");
		return "Base";
	}
	public static function index()
	{
		return User::All();
	}

	public static function show($request)
	{
		User::CheckAccessToken();
		return User::Select($request);
	}

	public static function store($request)
	{
		return User::Insert($request);
	}

	public static function update($request)
	{
		return User::Update($request);
	}

	public static function delete($request)
	{
		return User::Delete($request);
	}

	public static function search($request)
	{
		$ColumnsToSearch = ['name','email'];
		$OrderBy = ['id','desc'];
		return User::Search($request,$ColumnsToSearch,$OrderBy);
	}

}
