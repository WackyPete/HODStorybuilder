(function () {
    'use strict';
    var l_path = './lib/',
        f_path = './uploads/Story.xml',
        s_path = './samples/';
    var file;
    var tagCounter = 1;
    var dCharLen = 44;
    var tCharLen = 30;
    var input = $('#StoryInput');    

    input.bind('input propertychange', function () {
        var max = 1500;
        var curval = $(this).val().length;
        var txt = $('.txtCount');
        txt.text(curval);
        if (curval >= max) {
            txt.css('color', 'red');
        }
        else {
            txt.css('color', 'green');
        }
    });

    if (input.val() == '' && input != null) {
        input.val('[BOSSNAME] [BOSSTYPE] [REWARDNAME] [MAPNAME]');
    }
    
    function InputVal() {
        var tvalue = $('input[type=submit][clicked=true]').val();
        $('input[type=submit]').removeAttr('clicked');
        return tvalue;
    }

    function Reset() {
        tagCounter = 1;
        $('#StoryInput').val('');
        $('#StoryOutput').val('');
        $('.s-block').remove();
        $('.txtCount').text(0);
        $('.tagdump').empty();
        $('#StoryGroup').val('');
    }

    function MountFile(url, tagonly) {
        $.ajax({
            type: 'GET',
            cache: false,
            url: url,
            dataType: 'xml',
            success: function (xml) {
                ProcessFile(xml, tagonly);
                $('.upload-progress').hide();
            },
            error: function (err) {
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    }

    function PrepFile(event) {
        file = event.target.files;
    }

    function UpTags() {
        var tagLift = input.val();
        var rep = tagLift.replace(/(\[.*?\]|\{.*?\}|\*.*?\*)\s?/g, function (r) {
            return r.toUpperCase();
        });
        input.val(rep);
    }

    function ProcessFile(xml, tagonly) {
        var tags = [];
        var currentTags = [];
        var story = $(xml).find('Story').text();
        var storyGroup = $(xml).find('Group').text();
        
        $(xml).find('Story_Fields').each(function () {
            $(this).find('Field').each(function () {
                var name = $(this).attr('Name');
                tags[name] = [];
                $(this).find('Value').each(function () {
                    var val = $(this).text();
                    tags[name].push(val);
                });
            });
        });

        if (story.length > 0 && !tagonly) {
            Reset();
            $('#StoryInput').val(story);
            $('#StoryGroup').val(storyGroup);
            $('.txtCount').text(story.length);
        }        
        
        $('.s-tag').each(function () {
            var name = $(this).val();
            if (name != '') {
                currentTags.push(name);
            }
        });
            
        for (var tagname in tags) {

            var t_index = tagCounter;

            if (currentTags.indexOf(tagname) >= 0)
                continue;

            $('#AddTag').trigger('click');
            $('.s-tag').each(function () {
                var val = $(this).val();
                var v_index = 1;
                if (val == '') {
                    $(this).val(tagname);
                    for (var tagvalue in tags[tagname]) {
                        if (v_index > 1)
                            $('#' + t_index + '_TagValue').trigger('click');
                        $('#' + t_index + '_TagValue_' + v_index).val(tags[tagname][tagvalue]);
                        v_index++;
                    }
                }
            });
        }

        $('#InputFile').empty();
        StackTags();
    }

    function ClearField(event) {

        event.preventDefault();
        var id = '';
        if ($(this).attr('class') == 'tClear') {
            if (confirm('Do you want to delete this tag list?')) {
                id = $(this).children().attr('class').split(' ')[0];
                $('#' + id).remove();
                StackTags();
                //tagCounter++;
            }
        } else {
            id = $(this).children().attr('class');
            $('#' + id).remove();
        }
    }
    
    function StackTags() {

        var tagdump = $('.tagdump');
        tagdump.empty();
        $('.s-tag').each(function () {
            var val = $(this).val().toUpperCase();
            var id = $(this).attr('id');
            tagdump.append('<a href="#'+id+'" id="' + val + '" class="tagdrop">[' + val + ']</a> ');
        });
    }

    $('.tagdrop').click(function (e) {
        e.preventDefault();
        var pos = input.prop('selectionStart');
        var tag = '[' + $(this).attr('id').toUpperCase() + ']';
        var dropin = input.val().substr(0, pos) + tag + input.val().substr(pos);
        input.val(dropin);
    });

    $('#story').on('keypress', function (e) {
        if (e.keyCode === 13) {
            var fElement = $(':focus');
            var tagid = fElement.attr('id');
            if (tagid == 'StoryInput' || tagid == 'StoryOutput') return;
            e.preventDefault();
            tagid = tagid.substr(0, tagid.lastIndexOf('_'));
            var fNewElement = $('#' + tagid).trigger('click').get(0).clickid;
            $('#' + fNewElement).focus();
        }
    });

    $('#preview-story').click(function(e) {
        e.preventDefault();
        LoadInStory(e, 'Preview');
    });

    $('#export-story').click(function(e) {
        e.preventDefault();
        LoadInStory(e, 'Export');
    });

    $('#StoryOutput').click(function() {
        $(this).select();
    });

    function LoadInStory(event, tvalue) {
        UpTags();
        var fields = $('#story').serializeArray();
        $.ajax({
            type: 'POST',
            url: l_path + 'SubmitStory.php?submit=' + tvalue,
            data: {
                output: JSON.stringify(fields)
            },
            success: function (story) {

                if (story.indexOf('!TagError') >= 0) {
                    return alert(story);
                }

                if (tvalue == 'Preview') {
                    $('#StoryOutput').val(story);
                }
                else {
                    if (input.val().length <= dCharLen) {
                        alert('You have not made a story! Please enter a story and try export again');                    
                    } else {
                        window.location = l_path + 'Download.php';
                    }
                }

                StackTags();
            }
        });
    }

    $('#story input[type=submit],#upload_story input[type=submit]').click(function () {
        $(this).attr('clicked', 'true');
    });

    $('#clear-story').click(function (e) {
        e.preventDefault();
        if (confirm('Are you sure you want to clear all your story data?')) {
            Reset();
        }
    });

    $('.example-story').click(function (e) {
        e.preventDefault();
        $('.upload-progress').show();
        var fileID = $(this).attr('id') + '.xml';
        var file = s_path + fileID;
        Reset();
        MountFile(file, false);
    });

    $('#InputFile').on('change', PrepFile);

    $('#upload_story').on('submit', function (e) {

        e.stopPropagation();
        e.preventDefault();

        var s_value = InputVal();

        if (file == null) {
            alert('Please choose a file then try again');
            return;
        }

        var data = new FormData();

        $.each(file, function (key, val) {
            data.append(key, val);
        });

        $.ajax({
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            url: l_path + 'Upload.php',
            data: data,
            success: function (data) {
                if (data == "true") {
                    $('.upload-progress').show();
                    var tag_only = s_value == "Import Tags" ? true : false;
                    MountFile(f_path, tag_only);
                }
            }
        });


    });

    $('#AddTag').click(function (e) {

        e.preventDefault();

        var tagname = tagCounter + '_TagName';
        var tagvalue = tagCounter + '_TagValue';

        var blockdata = '<div id="d-' + tagname + '"><div class="col-md-5 s-block"><div class="panel panel-primary">' +
            '<div class="panel-heading"><h3>Story Tag [' + tagCounter + ']</h3><a class="tClear" href="#"><span class="d-' + tagname + ' badge">X</span></a></div><div class="panel-body">' +
            '<input id="' + tagname + '" name="' + tagname +
            '" type="text" class="form-control s-tag" placeholder="Ex. WEAPONS" value="" maxlength="16" size="16" spellcheck="true" /><br />' +
            '<label for="' + tagvalue + '_1">Set your tag values:</label>' +
            '<div class="' + tagvalue + '"><p class="AddValue" id="' + tagvalue + '"><a href="#"><span>Add Values</span></a></p>' +
            '<input id="' + tagvalue + '_1" name="' + tagvalue + '_1" type="text" class="form-control s-value" placeholder="Ex. Magnificent Long Sword" value="" spellcheck="true" />' +
            '</div><br /><a href="#top">[TOP]</a></div></div></div></div>';

        if (e.target.id === 'AddTag') {
            $('.insert_blocks').append(blockdata);
        } else {
            $('.insert_blocks').prepend(blockdata);
        }

        $('.tClear').off('click').one('click', ClearField);

        var tagValueCounter = 2;

        $('#' + tagvalue).click(function (ex) {
            ex.preventDefault();
            var id = $(this).attr('id');
            var tagid = id + '_' + tagValueCounter;
            $('.' + id).append('<div id="d-' + tagid + '"><input id="' + tagid + '" name="' + tagid + '" type="text" class="form-control s-value" value="" spellcheck="true" /><a class="vClear" href="#"><span class="d-' + tagid + '"></span> Clear</a></div>');
            this.clickid = tagid;
            tagValueCounter++;

            $('.vClear').click(ClearField);
        });

        tagCounter++;
    });

    $('#DeleteUnused').click(function (e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete your unused tags?')) {

            StackTags();
            var story_tags = $('#StoryInput').val().match(/(\[.*?\]|\{.*?\}|\*.*?\*)\s?/g);
            var del = false;

            for (var i = 0; i < story_tags.length; i++) {
                story_tags[i] = story_tags[i].replace('[', '')
                    .replace(']', '').replace('{', '').replace('}', '').replace(/\*/g, '');
                story_tags[i] = story_tags[i].trim();
                story_tags[i] = story_tags[i].toUpperCase();
            }

            $('.s-tag').each(function () {
                var val = $(this).val().toUpperCase();
                val = val.trim();
                if (story_tags.indexOf(val) === -1) {
                    var id = $(this).attr('id');
                    $('#d-' + id).remove();
                    StackTags();
                    //tagCounter++;
                    del = true;
                }
            });

            if (del) {
                alert('Tag(s) Deleted');
            }
        }
    });

}());