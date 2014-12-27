<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
    <head>
        <script type="text/javascript" src="raphael-min.js"></script>
        <script type="text/javascript" src="dracula_graffle.js"></script>
        <script type="text/javascript" src="jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="dracula_graph.js"></script>
 
        
      <body>
          
            <div style="text-align:center; font-size:30px"> Link Structure Visualizer </div>
                <?php 

                    //PARSING THE XML FILE 



                    //$xml = file_get_contents ($_GET["name"]);
                    $websiteURL = $_GET["name"];
                    $websiteSitemapURL = $_GET["name"] . "sitemap.xml";
                    $xml = simplexml_load_file($websiteSitemapURL);
                    //$xml = simplexml_load_file('sitemap_1.xml'); //How to use a static sitemap    
                    $xml_array = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
                    
                   // print_r($xml_array);

                   // echo "<br/><br/><br/> Total links: <br/>";

                    $i = 0;
                    $links = array();
                    $allWebLinks = array();

                    foreach ( $xml_array as $item ) 
                    {

                        foreach ( $item as $theLink ) 
                        { 
                            $links[$i] = $xml_array["url"]["$i"]["loc"]; 
                            $i++;
                        }
                    }

                 /*   for ($i=0; $i<sizeof($links); $i++) 
                    {
                        echo $links[$i]."<br/>";
                    }
                */    
                    echo "<br/><br/><br/>";

                    // GETTING THE LINKS FROM THE WEBSITE
                    
                    echo "Processed links: <br/>";
                   
                    $weblinksInternal = array();
                    $weblinksExternal = array();

                    for ($currentPage = 0; $currentPage < sizeof($links); $currentPage++) 
                    {
                        
                        echo $links[$currentPage]." </div> <br/>   ";

                        $doc = new DOMDocument();
                        $doc->loadHTMLFile($links[$currentPage]);


                          // all links in document
                   
                        
                          $arr = $doc->getElementsByTagName("a"); // DOMNodeList Object
                       
                          foreach($arr as $item) 
                          { // DOMElement Object
            
                            $currentHref = $links[$currentPage];  
                            $href =  $item->getAttribute("href");
                            $title = $item->getAttribute("title");  
                            $rel = $item->getAttribute("rel");
                            $text = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));

                            if (strpos($href,$websiteURL) !== false && strpos($href, 'pinterest.com') == false )  {
                                
                                    $weblinksInternal[] = array(
                                  'currenthref' => $currentHref,    
                                  'href' => $href,
                                  'text' => $text,
                                  'title' => $title,
                                  'rel' => $rel
                                     );
                                
                            }
                              
                            else {
                                  $weblinksExternal[] = array(
                              'currenthref' => $currentHref,    
                              'href' => $href,
                              'text' => $text,
                              'title' => $title,
                              'rel' => $rel
                                 );
                                
                            }
                              
                           
                                 
                           
                              
                            
                            $allWebLinksInternal[$currentPage] = $weblinksInternal;
                            $allWebLinksExternal[$currentPage] = $weblinksExternal; 
                            
                           
                          }
                        
                       
                   
                        
                        
                  /*      

                          for ($i=0; $i<sizeof($weblinks); $i++) 
                          {
                            echo "Current href = ".$weblinks[$i]['currenthref']."<br/>";
                            echo "Href = ".$weblinks[$i]['href']."<br/>";
                            echo "Text = ".$weblinks[$i]['text']."<br/>";
                            echo "Title = ".$weblinks[$i]['title']."<br/>";
                            echo "Rel = ".$weblinks[$i]['rel']."<br/><br/>";
                          }
                    */    
                        
                    
                    }

                        

                ?>
          
          
          
<!--  Internal Links JavaScript  ----------------------------------->          
          
          
          <script type="text/javascript">
              
              ////////////////// DATA PART //////////////////////////////
              
             Array.prototype.contains = function(v) {
                for(var i = 0; i < this.length; i++) {
                    if(this[i] === v) return true;
                }
                  return false;
              };

              Array.prototype.unique = function() {
                  var arr = [];
                  for(var i = 0; i < this.length; i++) {
            if(!arr.contains(this[i])) {
            arr.push(this[i]);
            }
                  }
                  return arr; 
            }   
              
                
              
              



          var allWebLinksInternal = <?php echo json_encode((array) $allWebLinksInternal) ?>;
          var allWebLinksExternal = <?php echo json_encode((array) $allWebLinksExternal) ?>;
        
          var currenthrefInternal = [];
          var currenthrefExternal = [];      
          
          var hrefInternal = [];       
          var hrefExternal = [];
              
          var myIndexInternal = 0;
          var myIndexExternal = 0;    
              
          var nodesInternal = [];
          var nodesExternal = [];
              
          var uniqueNodesInternal = [];
          var uniqueNodesExternal = [];
                
            for (var i=0; i<allWebLinksInternal.length; i++)
            {
                for (var j=0; j<allWebLinksInternal[i].length; j++)
                {
                    
                   currenthrefInternal [myIndexInternal] = allWebLinksInternal[i][j]["currenthref"];
                   hrefInternal [myIndexInternal] = allWebLinksInternal[i][j]["href"];
                   myIndexInternal++;

                }

            }
              
              
              for (var i=0; i<allWebLinksExternal.length; i++)
            {
                for (var j=0; j<allWebLinksExternal[i].length; j++)
                {
                    
                   currenthrefExternal [myIndexExternal] = allWebLinksExternal[i][j]["currenthref"];
                   hrefExternal [myIndexExternal] = allWebLinksExternal[i][j]["href"];
                   myIndexExternal++;

                }

            }
              
                
            nodesInternal = currenthrefInternal.concat(hrefInternal);  
            uniqueNodesInternal = nodesInternal.unique();
              
            nodesExternal = currenthrefExternal.concat(hrefExternal);
            uniqueNodesExternal = nodesExternal.unique();  
            
        
              
              
            ////////////////////////GRAPH PART///////////////////////////////  
            window.onload = function() {
              
            var width = $(document).width() - 20;
            var height = $(document).height() - 60;
      
              g = new Graph();
              h = new Graph();    
              
              
             for (var b=0; b<uniqueNodesInternal.length; b++) 
              {
                      g.addNode(uniqueNodesInternal[b]);
              }
                
                
             for (var b=0; b<uniqueNodesExternal.length; b++) 
              {
                      h.addNode(uniqueNodesExternal[b]);
              }    
              
                
                
                
               for (var i=0; i<allWebLinksInternal.length; i++)
               {
                   for (var j=0; j<allWebLinksInternal[i].length; j++)
                    {
                        g.addEdge(allWebLinksInternal[i][j]["currenthref"], allWebLinksInternal[i][j]["href"], { directed : true, } );
                    }
               }
                
                
                
                
                for (var i=0; i<allWebLinksExternal.length; i++)
               {
                   for (var j=0; j<allWebLinksExternal[i].length; j++)
                    {
                        h.addEdge(allWebLinksExternal[i][j]["currenthref"], allWebLinksExternal[i][j]["href"], { directed : true} );
                    }
               }
                
                 
                
                
              var layouter = new Graph.Layout.Spring(g);
              renderer = new Graph.Renderer.Raphael('InternalLinks', g, width, height);
            
              height += 500;
              var layouter = new Graph.Layout.Spring(h);
              renderer = new Graph.Renderer.Raphael('ExternalLinks', h, width, height);
                
              
             }
          
        
        
          </script>
          

          
        
          
<br/> All INTERNAL Links: <br/>     
         
<div id="InternalLinks">  </div>
          
<br/> All EXTERNAL Links:  <br/>        
<div id="ExternalLinks"> </div>          
          
          
        </body>
</html>