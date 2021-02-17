import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';

function Loader(){
	return(
		<div className="loader" id="loader"></div>
	)
}

function LoadMore(id,arrayImgs){
	//console.log(id);
	//console.log(arrayImgs);
	ReactDOM.render(arrayImgs,document.getElementById(id));
}


function LoadRemaining(props){
	var res=props.imgList;
	var divId="id"+props.info;
	var buttonId="idbut"+props.info;
	
	function greetUser() {
	    LoadMore(divId,res);
	}

	return(
		<div id={divId} >
			<p align="center" >
				<button className="btn btn-primary" onClick={greetUser} id={buttonId} >Show {props.info}</button>

			</p>
		</div>

	)
}
function Image(props){
	const url=props.url;
	const date=props.date;
	
	return(
		<div className="container" style={{paddingBottom:"40px"}} align="center">
			<p>{date}</p>
			<img src={url} className="img-fluid"/>
		</div>
	)
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
var apiArray=["xx","xx","xx","xx"];



function LoadNasaAPIs(ele){
	
	ReactDOM.render(<Loader/>,document.getElementById("divForResults"));
	var rover=ele.innerHTML.split(" ")[0];
	//console.log(rover);

	var date=document.getElementById("date").value;
	
	var apiKey=apiArray[getRandomInt(0,3)]; 
	var req = new XMLHttpRequest();
    var url = "https://api.nasa.gov/mars-photos/api/v1/rovers/"+rover+"/photos?earth_date="+date+"&api_key="+apiKey; 
    
    if(date==""){
		ReactDOM.render(<i>Invalid date</i>,document.getElementById("divForResults"));return;
 	}
    //const arr=[];
    req.open("GET", url);
    req.send();
   	

	req.addEventListener("load", function(){
      if(req.status == 200 && req.readyState == 4){
        var response = JSON.parse(req.responseText);
      	 //console.log(response);
  	 	if(response["photos"].length==0){
  	 		if(date==""){
				ReactDOM.render(<i>Invalid date</i>,document.getElementById("divForResults"));
  	 		}
  	 		else
      			ReactDOM.render(<i>No images taken by {rover} on {date}</i>,document.getElementById("divForResults")); 	
      	} 
       	else{
       		var arr=[];
       		var maxNoOfImgs=0;
       		var Mainres=[];
       		var idList=[];
       		var las=0;
       		var las2=0;
       		for(var a=0;a<response["photos"].length;a++){
		    	maxNoOfImgs++;las=a;
		    	if(maxNoOfImgs==50){
		    		idList.push(info1);
		    		var info1=a+1-maxNoOfImgs+"-"+a;
		    		Mainres.push(<LoadRemaining imgList={arr} info={info1} key={info1}/>)
		    		maxNoOfImgs=0;
	    			arr=[];
	    			las2=a;
		    	}

	    		const date=response["photos"][a]["camera"]["full_name"];
	    		    		
	    		const imgUrl=response["photos"][a]["img_src"];
	    		//document.getElementById("divForResults").appendChild(<Image/>);
	    		//console.log(data+" "+title+" "+imgUrl); 
	    		arr.push(<Image url={imgUrl} date={date} key={a}/>);
	    	}
	    	//document.getElementById("divForResults").innerHTML="<a style='display:none'>asda</a>";
	    	
	    	if(las<50){
	    		Mainres.push(<LoadRemaining imgList={arr} info={0+"-"+las} key={las}/>);
	    	}
	    	else if(arr.length!=0){
	    		Mainres.push(<LoadRemaining imgList={arr} info={las2+"-"+las} key={las}/>);
	    	}
	    	
	    	ReactDOM.render(Mainres,document.getElementById("divForResults"));
			document.getElementById("divForResults").onload=function(){alert('ad');}   	
       	}
	}
    else{
    	if(date==""){
    		ReactDOM.render(<i>Invalid date</i>,document.getElementById("divForResults"));
	 	}
	 	else
  			ReactDOM.render(<i>No images found for {rover} on {date}</i>,document.getElementById("divForResults")); 	
    }
    });

}

//Opportunity -- 2004-01-26 --> 2018-06-11
//spirit ---    2004-01-29  --> march 22 2010 || 2010-03-21
//Curiosity    2012-08-18 ----> present
class NasaApi extends React.Component{
	constructor(props){
		super(props);
		this.onChangeInput=this.onChangeInput.bind(this);
		this.curo=this.curo.bind(this);
		this.spirit=this.spirit.bind(this);
		this.oppr=this.oppr.bind(this);
		
				
	}
	render(){
		return(
			<div className="container" id="idd" style={{color:"white"}}  onScroll={this.onScroll}> 
				<h4>Select a rover and date</h4> 
				<p>Credits: NASA's Mars Rover Photos<a href="https://api.nasa.gov/"> <i className="fa fa-arrow-right" aria-hidden="true"></i></a></p>
				<a>Date: </a>
				<input type="date" id="date" name="date" style={{color:"white",backgroundColor:"rgb(60 60 65)",borderRadius:"6px" }} onChange={this.onChangeInput} />
				<br/><br/>
				<ul className="nav nav-pills justify-content-center" >
				  <li className="nav-item" style={{paddingLeft:"5px"}}>
				    <a className="nav-link active" id="1" data-toggle="tab" href="#"  onClick={this.curo}>Curiosity (18 Aug 2012 - present)</a>
				  </li>
				   <li className="nav-item" style={{paddingRight:"5px"}}>
				    <a className="nav-link" id="3" data-toggle="tab" href="#" onClick={this.oppr}>Opportunity (26 Jan 2004 - 11 Jun 2018)</a>
				  </li>

				  <li className="nav-item" style={{paddingLeft:"5px",paddingRight:"5px"}}>
				    <a className="nav-link" id="2" data-toggle="tab" href="#" onClick={this.spirit}>Spirit (29 Jan 2004 - 22 Mar 2010)</a>
				  </li>
				 
				  
				</ul>
				  
				  <br/>
				<p align="center">
					<button className="btn btn-primary" id="btnClick" onClick={this.onChangeInput} >Load...</button>
				</p>
				<br/>
				<div id="divForResults" onScroll={this.scroll} ></div>
    
			</div>
		)
	}
	
	onChangeInput(ele){
		//console.log(document.getElementById("date").value);
		if(document.getElementById("1").className=="nav-link active"){
			
			this.curo();
		}
		if(document.getElementById("2").className=="nav-link active"){
			//document.getElementById("divForResults").innerHTML="";
			this.spirit();
		}
		if(document.getElementById("3").className=="nav-link active"){
			//document.getElementById("divForResults").innerHTML="";
			this.oppr();
		}
	}
	curo(){
		LoadNasaAPIs(document.getElementById("1"));
	}
	spirit(){
		LoadNasaAPIs(document.getElementById("2"));
	}
	oppr(){
		LoadNasaAPIs(document.getElementById("3"));
	}

	
}

export default NasaApi;