<?php
/**********************************************************************
 * Title: MCQuick
 * Description: Application for creating and solving MCQ papers
 * Author: Agnibho Mondal
 * Website: http://code.agnibho.com
 **********************************************************************
   Copyright (c) 2014-2015 Agnibho Mondal
   All rights reserved
 **********************************************************************
   This file is part of MCQuick.
   
   MCQuick is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   MCQuick is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with MCQuick.  If not, see <http://www.gnu.org/licenses/>.
 **********************************************************************/
?>
<div class="modal fade" id="block-wait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<h4>Please wait while retrieving data from server</h4>
	    </div>
	</div>
    </div>
</div>

<div class="modal fade" id="connect-fail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<h4>Failed to connect server</h4>
	    </div>
	</div>
    </div>
</div>

<div class="modal fade" id="not-found" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<h4>The specified paper could not be found</h4>
	    </div>
	</div>
    </div>
</div>

<div class="modal fade" id="log_in_providers" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<h4>Log in</h4>
	    </div>
	    <div class="modal-body">
		<div class="row">
		    <div class="col-sm-8" style="border-right:1px solid gray">
			<form method="post">
			    <div class="form-group">
				<label for="email-modal">E-mail:</label>
				<input type="text" name="email" id="email-modal" class="form-control" placeholder="Enter your registered e-mail id">
			    </div>
			    <div class="form-group">
				<label for="password-modal">Password:</label>
				<input type="password" name="password" id="password-modal" class="form-control" placeholder="Enter your MCQuick password">
			    </div>
			    <button class="btn btn-success" type="button" id="login-modal">Log in</button>
			</form>
		    </div>
		    <div class="col-sm-4">
			<a href="login.php" class="btn btn-primary center-block">Create New Account</a>
			<hr>
			<a href="login.php?send=true" class="center-block">Forgot your password?</a>
		    </div>
		</div>
	    </div>
	</div>
    </div>
</div> 
