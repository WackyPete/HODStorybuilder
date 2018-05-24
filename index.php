<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
    <title>Story Creation Tool</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="ngen.css" />

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>   
</head>
<body>
    <a name="top"></a>
    <div class="container">                
        <div class="row">            
            <div class="col-md-12">
            <header>         
                <div class="page-header">
                    <h1>Heroes of Dire <small>Story Creation Tool</small></h1>
                </div>                       
            </header>            
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">Import Story</div>
                                <div class="panel-body">                                    
                                    <form id="upload_story" method="POST" action="">                                                                      
                                        <input class="btn btn-primary" id="Upload" type="submit" value="Upload Story" />&nbsp;<input class="btn btn-warning" id="Import" type="submit" value="Import Tags" />
                                        <br />
                                        <br />
                                        <input type="file" name="files" id="InputFile" accept=".xml" />
                                        <p class="help-block">Upload your modified story file here</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">Example Stories</div>
                            <div class="panel-body">
                                <p>
                                   <button id="Example1" type="button" class="btn btn-default example-story">Committing A Crime</button>
                                   <button id="Example2" type="button" class="btn btn-default example-story">Done Gone Missing</button>
                                   <button id="Example3" type="button" class="btn btn-default example-story">Caravan Spotting</button>
                                   <button id="Example4" type="button" class="btn btn-default example-story">The Spoiled Heiress</button>                                   
                                   <button id="Example5" type="button" class="btn btn-default example-story">Naive Student</button>
                                   <button id="Example6" type="button" class="btn btn-default example-story">Who Wants a Milkshake</button>
                                   <button id="Example7" type="button" class="btn btn-default example-story">The False Confession</button>
                                   <button id="Example8" type="button" class="btn btn-default example-story">Benjan Hurd</button>
                                   <button id="Example9" type="button" class="btn btn-default example-story">The Betrayed Samurai</button>
                                   <button id="Example10" type="button" class="btn btn-default example-story">Lyraen War Of Sucession</button>
                                </p>
                            </div>                            
                        </div>
                        <div class="upload-progress">
                            <div class="alert alert-info" role="alert">
                                <strong>Loading File</strong>&nbsp;&nbsp;Please wait...
                            </div>
                        </div>
                    </div>
                </div>
            <form id="story" method="POST" action="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">         
                                <input type="submit" class="btn btn-primary" id="export-story" name="process-story" value="Export" />                                
                                <input type="button" class="btn btn-info" id="clear-story" name="clear-story" value="Clear All" />
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="StoryGroup">Quest Group</label>
                                <input id="StoryGroup" name="StoryGroup" type="text" class="form-control s-value" placeholder="Enter your story group here" value="" spellcheck="true" />
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                    <label for="StoryInput">Create Story</label>
                                    <textarea id="StoryInput" name="StoryInput" class="form-control" rows="15" width="100%"></textarea> 
                                    <div>Character Count: <span class="txtCount">44</span></div>
                                <br/>
                                    <input type="submit" class="btn btn-warning btn-lg" id="preview-story" name="process-story" value="Preview" />
                                    <?php //<input type="submit" class="btn btn-info btn-lg" id="process-storytags" name="process-storytags" value="Scan" /> ?>
                            </div>
                            <div class="col-md-6">                            
                                <label for="StoryOutput">Preview Story</label>
                                <textarea id="StoryOutput" name="StoryOutput" class="form-control" rows="15" width="100%"></textarea>
                            </div>                        
                        </div>   
                        <br />                 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Add Required Tags</div>
                                    <div class="panel-body">
                                        <?php 
                                        include('/lib/NGenRequirements.php');                                    
                                        foreach(\Hod\StoryGen\NGenRequirements::$REQUIRED_TAGS as $val)
                                        {
                                            echo '<a href="#" id="'.$val.'" class="tagdrop">['.$val.']</a>  ';
                                        }

                                        ?>
                                    </div>
                                </div>   
                                 <div class="panel panel-default">
                                    <div class="panel-heading">Add Optional Tags</div>
                                    <div class="panel-body">
                                        <?php                                     
                                        foreach(\Hod\StoryGen\NGenRequirements::$OPTIONAL_TAGS as $val)
                                        {
                                            echo '<a href="#" id="'.$val.'" class="tagdrop">['.$val.']</a>  ';
                                        }
                                        ?>
                                    </div>
                                </div>      
                                <br /><br />                            
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Jump to Custom Tags</div>
                                    <div class="panel-body tagdump"></div>
                                </div>
                            </div>
                        </div>                                 
                        <div class="row well">
                            <div class="tag-container">
                                <p id="AddTag"><button type="button" class="btn btn-primary">Add New Tag</button></p>
                                <p id="DeleteUnused"><button type="button" class="btn btn-warning">Delete Unused Tags</button></p>
                            </div>                        
                            <div class="insert_blocks form-inline"></div>
                            <br /><br />
                        </div>                          
                </div>
            </form>
            </div>            
        </div>
    </div>
    <script src="ngen.min.js"></script>
</body>
</html>