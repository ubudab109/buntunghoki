<?php

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Route;

/**
 * It returns the value of a company setting if a slug is passed, or all company settings if no slug is
 * passed.
 * 
 * @param string slug The slug of the company setting you want to retrieve.
 * 
 * @return Collection collection of all the records in the company_settings table.
 */
function companySetting($slug = null)
{
	if (!is_null($slug)) {
		$data = CompanySetting::where('slug', $slug)->first()->value;
	} else {
		$data = CompanySetting::all();
	}
	return $data;
}

/**
 * If the current route is the route name passed in, return true, otherwise return false
 * 
 * @param string routeName The name of the route you want to check.
 * 
 * @return boolean
 */
function setActive($routeName, $output = 'active')
{
	if (is_array($routeName)) {
		foreach ($routeName as $u) {
			if (Route::is($u)) {
				return $output;
			}
		}
	} else {
		if (Route::is($routeName)) {
			return $output;
		}
	}
}


/**
 * If the current route is the route name passed in, return true, otherwise return false
 * 
 * @param string routeName The name of the route you want to check.
 * 
 * @return boolean
 */
function isActive($routeName)
{
	if (is_array($routeName)) {
		foreach ($routeName as $u) {
			if (Route::is($u)) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		if (Route::is($routeName)) {
			return true;
		} else {
			return false;
		}
	}
}


/**
 * It returns the value of a setting if it exists, otherwise it returns null.
 * 
 * @param slug The slug of the setting you want to get the value of.
 * 
 * @return The value of the setting.
 */
function getSettingValue($slug) {
	$companySetting = CompanySetting::where('slug', $slug)->first();
	if ($companySetting) {
		return $companySetting->value;
	}

	return null;
}

/**
 * Generate image name
 * @param String $extension
 * @return preg_replace random name
 */
function generateImageName($extension)
{
    return preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()) . '.' . $extension;
}

/**
 * Storing images to strage
 * @param Path to store
 * @param File
 * @return String file name
 */
function storeImages($path, $file)
{
    $extension = $file->getClientOriginalExtension();
    $imageName = generateImageName($extension);
    $file->storeAs(
        $path,
        $imageName
    );
    return $imageName;
}

function rupiah($angka){
	
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
 
}

