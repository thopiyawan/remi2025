<h2>Log In</h2>
    
    <form method="POST" action="/doctor_login">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="doctor_id">doctor_id:</label>
            <input type="doctor_id" class="form-control" id="doctor_id" name="doctor_id">
        </div>
 
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
 
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Login</button>
        </div>
      
    </form>

