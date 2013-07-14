/* @Author: Erick Wellem - me @ erickwellem.com - October 2009 */

function validateLoginBox (form) 
{

	//-- username validation
		if (form.frm_user_login.value == '' || form.frm_user_login.value == 'Please type your username') 
		{
			alert('Please type your username!')
			form.frm_user_login.value = ''
			form.frm_user_login.focus()
			return false;
		}

	//-- password validation
		if (form.frm_user_password.value == '') 
		{
			alert('Ketikkan password Anda!')
			form.frm_user_password.value = ''
			form.frm_user_password.focus()
			return false
		}
		
		form.frm_submit.value = 'Loading...'
}

function confirmLogout(form) 
{				
	var confirmed = confirm('You are going to be logged out from the system! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}


function confirmDeletePhoto(form) 
{				
	var confirmed = confirm('You are going to delete this photo! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}

function confirmDeleteImage(form) 
{				
	var confirmed = confirm('You are going to delete this image! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}

function confirmDeleteUser(form) 
{				
 	var confirmed = confirm('You are going to delete this user! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}

function confirmDeleteUserGroup(form) 
{				
 	var confirmed = confirm('You are going to delete this user group! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}

function confirmDeleteLog(form) 
{				
	var confirmed = confirm('You are going to delete this log! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}

function confirmDeleteModule(form) 
{				
	var confirmed = confirm('You are going to delete this module! Are you sure?')
	if (confirmed == false)				
	{
		return false
	}
}

function validateSearch(form) 
{
			
	//-- frm_search_text
	if (form.frm_search_text.value == '') 
	{
		alert('Please fill text for search!')
		form.frm_search_text.value = ''
		form.frm_search_text.focus()
		return false
	}				
	
	form.frm_search_submit.value = 'Loading...'
}

/****************************************************************************
* User Custom Functions
****************************************************************************/
function confirmDeleteActivity() 
{
	var confirmed = confirm('You are going to delete this Activity! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}

function confirmDeleteProduct() 
{
	var confirmed = confirm('You are going to delete this Product! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}

function confirmDeleteSize() 
{
	var confirmed = confirm('You are going to delete this Size! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}

function confirmDeleteStore() 
{
	var confirmed = confirm('You are going to delete this Store! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}

function confirmDeleteSupplier() 
{
	var confirmed = confirm('You are going to delete this Supplier! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}

function confirmDeleteBooking() 
{
	var confirmed = confirm('You are going to delete this Booking! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}

function confirmDeleteBookingActivity() 
{
	var confirmed = confirm('You are going to delete this Activity from this Booking! This action cannot be undone. Are you sure?')
	if (confirmed == false)				
	{
		return false;
	}

	else
	{
		return true;
	}
	
}
