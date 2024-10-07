<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<title>Employee Admin Page</title>
</head>
<body>
    <h2 style="text-align:center; margin-top: 75px; margin-bottom: 55px;">Employee Admin Page</h2>
        <div >
            <div style="margin-top: 25px; display: flex; justify-content: center;" >
                <label>EID:</label><input style="margin-left:90px;" type="text" id="eid" name="eid" >
                <label style="margin-left: 25%;">Department:</label><input style="margin-left:90px;" type="text" id="dep" name="dep" >
            </div>

            <div style="margin-top: 25px; display: flex; justify-content: center;" >
                <label>Name:</label><input style="margin-left:90px;" type="text" id="name" name="name" >
                <label style="margin-left: 25%;">Mobile No.:</label><input style="margin-left:90px;" type="text" id="mn" name="mn" >
            </div>

            <div style="margin-top: 25px; display: flex; justify-content: center;">
                <label>E-mail</label><input style="margin-left:90px;" type="email" id="email" name="email" >
                <label style="margin-left: 25%;">Title:</label><input style="margin-left:90px;" type="text" id="title" name="title" >
            </div>

            <div style="margin-top: 25px; display: flex; justify-content: center;" >
                <label>Reporting Manager:</label><input style="margin-left:90px;" type="text" id="rm" name="rm" >
            </div>
        </div>

    <div style="margin-top: 25px; display: flex; justify-content: center; padding:4px;">
        <button type="button" class="btn btn-secondary" style="margin-right: 30px;">Find</button>
        <button type="button" class="btn btn-secondary" style="margin-right: 30px;">Add</button>
        <button type="button" class="btn btn-secondary" style="margin-right: 30px;">Update</button>
        <button type="button" class="btn btn-secondary" style="margin-right: 30px;">Clear</button>
        <button type="button" class="btn btn-danger" style="margin-right: 30px;">Delete</button>
        

    </div>



    <div class="w-75 p-3">
        <table class="table table-sm" style="margin-left:15%; margin-top: 25px;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">EID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Mobile No.</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Title</th>
                    <th scope="col">Reporting Manager</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                
            </tbody>
        </table>
    </div>
    
    <div class="pt-1 mb-3 pb-1" style="display: flex; justify-content: space-between;">
        <a href="Login.php">
            <button class="btn btn-danger fa-lg gradient-custom-2" style="margin-left:50px; width:100px; margin-top:50px;" type="logout" name="logout">Logout</button>
        </a>
        
        <a href="Home.php">
            <button class="btn btn-primary fa-lg gradient-custom-2" style="margin-right:50px; width:100px; margin-top:50px;" type="home" name="home">Home</button>
        </a>
    </div>



</body>
<style>
   
</style>
</html>