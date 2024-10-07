<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manual</title>

    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multipoint Report</title>

    <!-- Chart JS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.4.1/chart.min.js" integrity="sha512-5vwN8yor2fFT9pgPS9p9R7AszYaNn0LkQElTXIsZFCL7ucT8zDCAqlQXDdaqgA1mZP47hdvztBMsIoFxq/FyyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- GOOGLE FONTS CDNS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">

    <!-- JQUERY CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  

    <!-- BOOTSTRAP CDNS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- DATATABLE CDNS-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    
    <!-- CUSTOM STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="style.css">

    <script type="text/javascript">
        $(document).ready(function(){
            var category_list        = ["Strategic Ability & Sound Business Judgement","Innovation & Change Agent","Initiative & Follow Through","Effective Communication","Customer Orientation and Team Work","Building High Performance Teams and Developing People","Decision Making and Problem Solving","Displays Ownership"];
            var questions_list       = {"1":["Anticipate the changes in the external environment and can understand and relate its impact on the current business.","Anticipate the changes within the team\/department\/SBU and is able to understand its impact on the current business.","Is able to understand the strategic intent and direction of the business and ADVANTIS."],"2":["Is able to come up with 'out of the box' ideas in executing assigned duties.","Is innovative: willing to try something new\/challenging if it offers improvement.","Puts systems in place to encourage\/reward innovation efforts by the team members.","Is a change Champion (proactive change agent).","Leads the way in challenging status quo.","Go for new initiatives that impact business positively.","Has energy and can energize others."],"3":["Shares lessons learnt from past experiences and encourage the team to incorporate best practices .","Gives careful attention to compliance standards, systems and procedures within team\/department\/ADVANTIS.","Has ability to get others committed and drive key priorities that drive results ."],"4":["Listens attentively and attempts to understand the perspectives of others .","Speaks effectively in front of a group.","Encourages others to express their views even if these are contrary to own opinions .","Keeps people up to date with information .","Accepts criticism openly and non-defensively.","Listens to people without interrupting .","Communicates with all stakeholders professionally and diplomaticaly."],"5":["Collaborates well within and across teams in ADVANTIS.","Builds strategic relationships (e.g. intra - organization, customers, government, partners, industry groups).","Makes the team clear on ADVANTIS DNA and related contributions from each team member.","Empowers team members to act.","Able to resolve conflicts and drive collaboration within the team.","Helps create a climate of trust within the team."],"6":["Encourages people to work outside of their comfort zone.","Provides timely and effective performance feedback to staff.","Is able to create a sense of commitment in the team towards achieving goals\/ objectives.","Effectively coaches and develops people.","Provides the due recognition to who deserves in front of the team .","Provides opportunities to learn\/aquire skills related to work\/required competencies.","Fosters an environment to drive team towards achieving team\/organizational goals."],"7":["Looks at problems from different perspectives and generate multiple solutions.","Is able to make logical explanations by looking at multiple data\/facts and information when making a decision.","Is able to make a positive contribution at a discussion.","Demonstrates the ability and judgment in taking risks .","Systematically search for issues that could be potential issues in the future ."],"8":["Demonstrates energy and passion even when tackling challenging business issues .","Takes the lead in complex situations and convinces others to gain support for an idea or cause .","Drives collective responsibility through appropriate actions( such as involving employees in goal-setting, ongoing communication).","Takes ownership for end-to-end outcomes."]};
            var reporting_manager    = [4.333333333333333,4.285714285714286,4,4,4,4.285714285714286,4.8,4.25];
            var self                 = [4,3.5714285714285716,4,3.4285714285714284,3.1666666666666665,3.4285714285714284,3.2,4];
            var peer                 = [4,3.5238095238095237,3.7777777777777777,3.7857142857142856,3.7222222222222223,3.738095238095238,4.1,3.875];
            var direct_reports       = [3.7333333333333334,3.3714285714285714,3.7333333333333334,3.1714285714285713,3.3666666666666667,3.4285714285714284,3.8,3.7];
            var reference_group      = [4.022222222222222,3.7269841269841266,3.837037037037037,3.6523809523809523,3.696296296296296,3.8174603174603177,4.233333333333333,3.9416666666666664];
            var question_avg_by_surveyor_type = {"ReportingManager":{"1":{"1":2.5,"2":1.5,"3":2.5},"2":{"1":2.5,"2":2,"3":2,"4":2,"5":2.5,"6":2,"7":2},"3":{"1":2.5,"2":1.5,"3":2},"4":{"1":2,"2":1.5,"3":2,"4":2.5,"5":2,"6":2,"7":2},"5":{"1":2,"2":2,"3":2,"4":2.5,"5":2,"6":1.5},"6":{"1":2.5,"2":2,"3":2,"4":2.5,"5":2,"6":2.5,"7":1.5},"7":{"1":2.5,"2":2.5,"3":2,"4":2.5,"5":2.5},"8":{"1":2,"2":2.5,"3":2,"4":2}},"Self":{"1":{"1":2,"2":2,"3":2},"2":{"1":2,"2":2,"3":1.5,"4":2,"5":2,"6":1.5,"7":1.5},"3":{"1":2,"2":2,"3":2},"4":{"1":1.5,"2":1.5,"3":2,"4":1.5,"5":2,"6":2,"7":1.5},"5":{"1":1.5,"2":2,"3":1,"4":2,"5":1.5,"6":1.5},"6":{"1":2,"2":1.5,"3":1.5,"4":1.5,"5":2,"6":2,"7":1.5},"7":{"1":2,"2":1.5,"3":1.5,"4":1.5,"5":1.5},"8":{"1":2,"2":2,"3":2,"4":2}},"Peer":{"1":{"1":3.5714285714285716,"2":3.2857142857142856,"3":3.4285714285714284},"2":{"1":3.2857142857142856,"2":3,"3":2.857142857142857,"4":3.2857142857142856,"5":2.7142857142857144,"6":3.142857142857143,"7":2.857142857142857},"3":{"1":3.7142857142857144,"2":3.2857142857142856,"3":2.7142857142857144},"4":{"1":3.2857142857142856,"2":3.2857142857142856,"3":3,"4":3.5714285714285716,"5":2.7142857142857144,"6":3.142857142857143,"7":3.7142857142857144},"5":{"1":3.4285714285714284,"2":3.2857142857142856,"3":3,"4":3.2857142857142856,"5":3.142857142857143,"6":3},"6":{"1":2.857142857142857,"2":3.2857142857142856,"3":3.2857142857142856,"4":3.142857142857143,"5":3.2857142857142856,"6":3.2857142857142856,"7":3.2857142857142856},"7":{"1":3.5714285714285716,"2":3.5714285714285716,"3":3.857142857142857,"4":3.142857142857143,"5":3.4285714285714284},"8":{"1":3.4285714285714284,"2":3.2857142857142856,"3":3.142857142857143,"4":3.4285714285714284}},"DirectReport":{"1":{"1":3.3333333333333335,"2":3,"3":3},"2":{"1":3.3333333333333335,"2":2.5,"3":2.8333333333333335,"4":2.6666666666666665,"5":2.8333333333333335,"6":2.6666666666666665,"7":2.8333333333333335},"3":{"1":3.1666666666666665,"2":3,"3":3.1666666666666665},"4":{"1":2.8333333333333335,"2":3.1666666666666665,"3":2.5,"4":2.8333333333333335,"5":2,"6":2.6666666666666665,"7":2.5},"5":{"1":3,"2":2.8333333333333335,"3":3,"4":2.6666666666666665,"5":2.6666666666666665,"6":2.6666666666666665},"6":{"1":2.6666666666666665,"2":2.6666666666666665,"3":3,"4":2.5,"5":3,"6":3.1666666666666665,"7":3},"7":{"1":3.1666666666666665,"2":3.1666666666666665,"3":3.1666666666666665,"4":3.3333333333333335,"5":3},"8":{"1":2.8333333333333335,"2":3,"3":3.1666666666666665,"4":3.3333333333333335}}};

            var category_count = category_list.length;

            console.log(category_list);
            console.log(reporting_manager);
            console.log(self);
            console.log(peer);
            console.log(direct_reports);
            console.log(reference_group);
            console.log(question_avg_by_surveyor_type);
            console.log(questions_list);

            // Evaluation Summery Page 3

            new Chart(document.getElementById("summery_radar"), {
                type: 'radar',
                data: {
                  labels: category_list,
                  datasets: [
                        {
                            label: "Reporting Manager",
                            fill: false,
                            borderColor: "rgba(255, 195, 0,1)",
                            pointBorderColor: "#FFCC00",
                            pointBackgroundColor: "rgba(255, 195, 0,1)",
                            data: reporting_manager
                        }, {
                            label: "Self",
                            fill: false,
                            borderColor: "rgba(199, 0, 57,1)",
                            pointBorderColor: "#C70039",
                            pointBackgroundColor: "rgba(199, 0, 57,1)",
                            data: self
                        },
                        {
                            label: "Peer",
                            fill: false,
                            borderColor: "rgba(57, 189, 18,1)",
                            pointBorderColor: "#39BD12",
                            pointBackgroundColor: "rgba(57, 189, 18,1)",
                            data: peer
                        }, {
                            label: "Direct Report",
                            fill: false,
                            borderColor: "rgba(11, 54, 160,1)",
                            pointBorderColor: "#0B36A0",
                            pointBackgroundColor: "rgba(11, 54, 160,1)",
                            data: direct_reports
                        }
                  ]
                },
                options: {
                  title: {
                    display: true,
                    text: 'Evaluation Summery - Radar Graph'
                  },
                  scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            min: 0,
                            stepSize: 1
                        }
                    }
                  }
                }
            });

            // Evaluation Summery (Self vs Reference Group) Page 4

            new Chart(document.getElementById("summery_bar_self_vs_reference"), {
                type: 'bar',
                data: { 
                    labels: category_list,
                    datasets: [
                        {
                            label: "Self",
                            backgroundColor: "rgb(199, 0, 57)",
                            data: self
                        },
                        {
                            label: "Reference Group",
                            backgroundColor: "rgb(25, 140, 4)",
                            data: reference_group
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    barValueSpacing: 20,
                    scales: {
                        y: {
                            ticks: {
                                min: 0, // Start the axis at zero
                                max: 5, // Set maximum rating to 5
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Evaluation Summery (Self vs Reference Group) Page 5

            new Chart(document.getElementById("summery_bar_self_vs_all_types"), {
                type: 'bar',
                data: { 
                    labels: category_list,
                    datasets: [
                        {
                          label: "Self",
                          backgroundColor: "rgb(199, 0, 57)",
                          data: self
                        },
                        {
                          label: "Reporting Manager",
                          backgroundColor: "rgb(255, 195, 0)",
                          data: reporting_manager
                        },
                        {
                          label: "Peer",
                          backgroundColor: "rgb(57, 189, 18)",
                          data: peer
                        }, 
                        {
                          label: "Direct Report",
                          backgroundColor: "rgb(11, 54, 160)",
                          data: direct_reports
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    barValueSpacing: 20,
                    scales: {
                        y: {
                            ticks: {
                                min: 0,
                                max: 5, // Set maximum rating to 5
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Question wise rating breakdown from Page 6 onwards

            console.log("-------------------------------");
            $.each( category_list, function( key, value ) {
                key++;
                var elementID = "category_no_" + key;

                var self_ratings = $.map(question_avg_by_surveyor_type['Self'][key], function(v) {
                    return v;
                });
                var rm_ratings = $.map(question_avg_by_surveyor_type['ReportingManager'][key], function(v) {
                    return v;
                });
                var dr_ratings = $.map(question_avg_by_surveyor_type['DirectReport'][key], function(v) {
                    return v;
                });
                var peer_ratings = $.map(question_avg_by_surveyor_type['Peer'][key], function(v) {
                    return v;
                });

                console.log("Self" + JSON.stringify(self_ratings));
                console.log("Reporting Managers" + JSON.stringify(rm_ratings));
                console.log("Direct Reporting" + JSON.stringify(dr_ratings));
                console.log("Peer" + JSON.stringify(peer_ratings));
                console.log("-------------------------------");

                new Chart(document.getElementById(elementID), {
                    type: 'bar',
                    data: { 
                        labels: questions_list[key],
                        datasets: [
                            {
                              label: "Self",
                              backgroundColor: "rgb(199, 0, 57)",
                              data: self_ratings
                            },
                            {
                              label: "Reporting Manager",
                              backgroundColor: "rgb(255, 195, 0)",
                              data: reporting_manager
                            },
                            {
                              label: "Peer",
                              backgroundColor: "rgb(57, 189, 18)",
                              data: peer
                            }, 
                            {
                              label: "Direct Report",
                              backgroundColor: "rgb(11, 54, 160)",
                              data: direct_reports
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        barValueSpacing: 20,
                        scales: {
                            y: {
                                ticks: {
                                    min: 0,
                                    max: 5, // Set maximum rating to 5
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });             
            });
        });
    </script>
</head>
</head>
<body>
    <h1 style="text-align: center;">Shareek Ahmed</h1><br>
    <hr>
    <div class="pagebreak"></div>

    <!-- -------------------------------------------------------------- -->

    <h1>Evaluation Summery</h1><br>
    <p class="paragraph">Summery of the ratings of the four surveyor types (Self, Reporting Manager, Peers, Direct Reports) for the seven rating questions. (Excluding text questions)</p><br>
    <center>
        <div style="width: 80%; border: 1px solid black;">
            <canvas id="summery_radar" width="1016" height="1016" style="display: block; box-sizing: border-box; height: 1016px; width: 1016px;"></canvas>
        </div>      
    </center>

    <hr>
    <div class="pagebreak"> </div>

    <!-- -------------------------------------------------------------- -->


    <h1>Evaluation Summery (Self vs Reference Group)</h1><br>
    <p class="paragraph">Reference Group - Average rating of the Reporting Manager(s), Peer(s), Direct Report(s)</p><br>
    <center>
        <div style="width: 80%; border: 1px solid black;">
            <canvas id="summery_bar_self_vs_reference" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
        </div>      
    </center>

    <hr>
    <div class="pagebreak"> </div>

    <!-- -------------------------------------------------------------- -->

    <h1>Evaluation Summery (Self vs Reporting Manager, Peers, Direct Reports)</h1><br>
    <center>
        <div style="width: 80%; border: 1px solid black;">
            <canvas id="summery_bar_self_vs_all_types" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
        </div>      
    </center>

    <hr>
    <div class="pagebreak"> </div>

    <!-- -------------------------------------------------------------- -->

    <h1><u>Category Deep Dive</u></h1>

    
        <h2>1) Strategic Ability &amp; Sound Business Judgement</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_1" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results1"></div>
        <br>
        
    
        <h2>2) Innovation &amp; Change Agent</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_2" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results2"></div>
        <br>
        
    
        <h2>3) Initiative &amp; Follow Through</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_3" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results3"></div>
        <br>
        
    
        <h2>4) Effective Communication</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_4" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results4"></div>
        <br>
        
    
        <h2>5) Customer Orientation and Team Work</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_5" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results5"></div>
        <br>
        
    
        <h2>6) Building High Performance Teams and Developing People</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_6" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results6"></div>
        <br>
        
    
        <h2>7) Decision Making and Problem Solving</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_7" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results7"></div>
        <br>
        
    
        <h2>8) Displays Ownership</h2><br>
        <center>
            <div style="width: 80%; border: 1px solid black;">
                <canvas id="category_no_8" width="1016" height="508" style="display: block; box-sizing: border-box; height: 508px; width: 1016px;"></canvas>
            </div>      
        </center>
        <div id="results8"></div>
        <br>
        
    
    <hr>

    <!-- -------------------------------------------------------------- -->



</body>
</html>