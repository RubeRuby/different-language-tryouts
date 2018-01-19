<?php
/*
	The following code is used to create fake data and insert it into the database.
	This is useful when you want to completely test the API of an application.
	This is written in Laravel, which comes with the PHP Faker library (only if you use Laravel 5 or higher).
*/

//Example of ModelFactory.php.
$factory->define(Employee::class, function (Generator $faker) {
	$fname = '';
	$gender = '';
	$plname = $faker->randomElement(['', 'a', 'de', 'der', 'den']);
	$lname = $faker->lastName;

	$x = rand(1, 4);
	switch( $x ) {
		case 1:
			$fname = $faker->firstNameFemale;
			$gender = 'female';
			break;
		case 2:
			$fname = $faker->firstNameMale;
			$gender = 'male';
			break;
		case 3:
			$fname = $faker->firstNameFemale;
			$gender = 'other';
			break;
		case 4:
			$fname = $faker->firstNameMale;
			$gender  = 'other';
			break;
	}


	return [
		'email' => fakeEmail($fname,$plname,$lname),
		'phone_number' => $faker->e164PhoneNumber,
		'first_name' => $fname,
		'prefix_last_name' => $plname,
		'last_name' => $lname,
		'birth_date' => $faker->dateTimeBetween($startDate = '-90 years', $endDate = '-16 years', $timezone = date_default_timezone_get()),
		'gender' => $gender,
		'street' => $faker->streetName,
		'house_number' => $faker->numberBetween($min = 0, $max = 200),
		'zip_code' => $faker->postcode,
		'city' => $faker->city,
		'img_path' => "images/" . $faker->md5 . ".jpg",
	];
});

//Example of DummyDataSeeder.php
DB::table('contracts')->delete();
DB::table('types')->delete();
DB::table('skills')->delete();
DB::table('projects')->delete();
DB::table('contacts')->delete();
DB::table('companies')->delete();
DB::table('sites')->delete();
DB::table('employees')->delete();
DB::table('cvs')->delete();
DB::table('questions')->delete();

/*Creates Employees and associates a country*/
$countries->random(10)->each(function($country) use ($skills, $types,
	$projects, $nationalities,
	$languages, $countries) {
	factory(Employee::class, 3)->create(['country_id' => $country->id])->each(function($e) use ($skills, $types,
		$projects, $nationalities,
		$languages, $countries) {
		/*Creates and attaches Sites to each Employee*/
		$e->sites()->attach(factory(Site::class, 2)->create());
		/*Creates and attaches Skills to each Employee*/
		$e->skills()->attach($skills->random(5));
		/*Creates and attaches Types to each Employee*/
		$e->types()->attach($types->random());
		/*Attaches Projects to each Employee*/
		$e->projects()->attach($projects->random(2));
		/*Attaches a Nationality to each Employee*/
		$e->nationalities()->attach($nationalities->random());
		/*Attaches Languages to each Employee*/
		$e->languages()->attach($languages->random());
	});
});

/*Creates CVs and Contracts, also links them to a employee*/
Employee::all()->random(10)->each(function($employee) use ($projects, $companies) {
	$employee->cvs()->saveMany(factory(Cv::class, 2)->make());
	factory(Contract::class)->create([
         'employee_id' => $employee->id,
         'type'        => 'competa'
     ]);

	if( rand(1, 2) === 1 ) {
		/*Creates Contracts and links them to a Employee, Project and Company*/
		factory(Contract::class)->create([
             'employee_id' => $employee->id,
             'type'        => 'company',
             'project_id'  => $projects->random()->getKey(),
             'company_id'  => $companies->random()->getKey()
         ]);
	} else {
		/*Creates Contracts and links them to a Employee and Project*/
		factory(Contract::class)->create([
		     'employee_id' => $employee->id,
		     'type'        => 'company',
		     'project_id'  => $projects->random()->getKey()
		 ]);
	}
}

//Some example functions in a controller

class UserController extends Controller {
	public static $defaultDash = [
		'1' => ['x' => 0, 'y' => 0],
		'2' => ['x' => 1, 'y' => 1],
		'3' => ['x' => 2, 'y' => 2],
		'4' => ['x' => 3, 'y' => 3]
	];

	/**
	 * Retrieve the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(int $id) {
		if(!$user = User::find($id)) return response()->json([ 'error' => trans('user.message.not_found')], 404);
		return response()->json( $user );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserRequest $request) {
		$user = User::create( $request->all() );
		$user->widgets()->sync(self::$defaultDash);
		return response( $user, 201);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\UserRequest  $request
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserRequest $request, int $id) {
		if(!$user = User::find($id)) return response()->json([ 'error' => trans('user.message.not_found')], 404);
		$user->fill( $request->all() );
		$user->save();
		return response()->json( $user );
	}

	/**
	 * Archive the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function archive(int $id) {
		if(!$user = User::find($id)) return response()->json([ 'error' => trans('user.message.not_found')], 404);
		$user->delete();
		return response()->json('', 204);
	}

	/**
	 * Restore the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function restore(int $id) {
		if(!$user = User::onlyTrashed()->find($id)) return response()->json([ 'error' => trans('user.message.not_found')], 404);
		$user->restore();
		return response()->json('', 204);
	}

	/**
	 * Destroy the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(int $id) {
		if(!$user = User::withTrashed()->find($id)) return response()->json([ 'error' => trans('user.message.not_found')], 404);
		if(!$user->trashed()) return response()->json([ 'error' => trans('user.message.not_archived') ], 423);
		$user->forceDelete();
		return response()->json('', 204);
	}

	public function updateDashboard(DashboardRequest $request) {
		$user = $request->user();
		$length = count($request->widgets);
		$array = $request->widgets;
		for ($i = 1; $i < $length+1; $i++) {
			$array[$i]['x'] = $array[$i][0];
			unset($array[$i][0]);
			$array[$i]['y'] = $array[$i][1];
			unset($array[$i][1]);
		}
		$user->widgets()->sync($array);
		return response()->json($user, 200);
	}
}