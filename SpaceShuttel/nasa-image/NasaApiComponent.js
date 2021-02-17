import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';

function Image(props){
	const url=props.url;
	const title=props.title;
	const desc=props.desc;
	return(
		<div className="container" style={{paddingBottom:"40px"}} align="center">
			<h5>{title}</h5>
			<img src={url} className="img-fluid"/>
			<details id="a" style={{padding:"6px"}} >
				<summary style={{outline: "none"}} id="detailsId">Read more</summary> 
				{desc}
			</details>
		</div>
	)
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
var apiArray=["gtiZgqXuP8f3OPyjqu21ysauFO8mCOyDPjRKwhJq","HQv9ho1mQR9um5vu34dEhP5lsedm95MEYOz3rfBh","W9OdIjEe1fWDQYstoh8lHgf1GtHKBAdbkS1VzcQ5","5B6oJsSCQyekXZvNOKpsUhRPl1e7FHqjIAyHpybk"];

function LoadNasaAPIs(topic,description){
	topic=topic.toLowerCase(); 	

	//console.log(topic); 
	//console.log(description);
	//console.log(getRandomInt(0,4));
	var apiKey=apiArray[getRandomInt(0,3)]; 
	var req = new XMLHttpRequest();
    var url = "https://images-api.nasa.gov/search?q="+topic+"&media_type=image&description="+description; 
    
    const arr=[];
    req.open("GET", url);
    req.send();
	req.addEventListener("load", function(){
      if(req.status == 200 && req.readyState == 4){
        var response = JSON.parse(req.responseText);
      	// console.log(response);
      	// console.log(response["collection"]["items"]); 
             
        document.getElementById("loader").style.display="none";
	    if(response["collection"]["items"].length==0){
	    	document.getElementById("divForResults").innerHTML="<i>No results found</i>";
	    }
	    else{
	    	for(var a=0;a<response["collection"]["items"].length;a++){
	    		const data=response["collection"]["items"][a]["data"][0]["description"];
	    		const title=response["collection"]["items"][a]["data"][0]["title"];
	    		
	    		const imgUrl=response["collection"]["items"][a]["links"][0]["href"];
	    		//document.getElementById("divForResults").appendChild(<Image/>);
	    		//console.log(data+" "+title+" "+imgUrl); 
	    		arr.push(<Image url={imgUrl} title={title} desc={data} key={a}/>);
	    	}
	    	ReactDOM.render(arr,document.getElementById("divForResults"));
	    }
    }
    });

}
class NasaApi extends React.Component{
	constructor(props){
		super(props);
		this.onChangeInput=this.onChangeInput.bind(this);
	}
	render(){
		return(
			<div className="container" id="idd" style={{color:"white"}}> 
				<h4>Enter the image query topic or description in keywords <small><small>(Ex: mars,andromeda)</small></small></h4> 
				<p>Credits: NASA's Image Library<a href="https://api.nasa.gov/"> <i className="fa fa-arrow-right"  aria-hidden="true"></i></a></p>
				<br/>
				<input className="form-control border-secondary py-2" id="searchIDTopic" onChange={this.onChangeInput} type="search" placeholder="Topic.." style={{color:"white", borderRadius:"40px",backgroundColor:"#131316"}}></input>	
				<br/>
				<input className="form-control border-secondary py-2" id="searchIDDescription" onChange={this.onChangeInput} type="search" placeholder="Description.." style={{color:"white", borderRadius:"40px",backgroundColor:"#131316"}}></input>	
				<br/>
				<p align="center">
					<button className="btn btn-primary" onClick={this.onChangeInput}>Load...</button>
				</p>
				<br/>
				<div className="loader" id="loader" style={{display:"none"}}></div> 
				
				<br/>
			</div>
		)
	}
	onChangeInput(ele){
		const topic=document.getElementById("searchIDTopic").value;
		const desc=document.getElementById("searchIDDescription").value
	
		if(topic!="" || desc!=""){
			ReactDOM.unmountComponentAtNode(document.getElementById("divForResults"));
			//document.getElementById("divForResults").innerHTML="";
			document.getElementById("loader").style.display="block"; 
			LoadNasaAPIs(topic,desc);
		}
	}

}

export default NasaApi;