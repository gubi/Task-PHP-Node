// import 'dotenv/config';
import express from "express"

const app = express()
const csv = require("csvtojson")
const converter=csv({
	noheader: false,
	trim: true,
    delimiter: ";"
})
const csvFilePath = "../data.csv"

var obj = ""
converter.fromFile(csvFilePath).then((jsonObj)=>{
    obj = jsonObj;
})

/**
 * Function to sort alphabetically an array of objects by some specific key.
 *
 * @param {String} property Key of the object to sort.
 */
function dynamicSort(property) {
    var sortOrder = 1;

    if(property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1);
    }

    return function (a,b) {
        if(sortOrder == -1){
            return b[property].localeCompare(a[property]);
        }else{
            return a[property].localeCompare(b[property]);
        }
    }
}



app.listen(3000, () =>
	console.log("Listening on port 3000!"),
);

app.get("/", (req, res) => {
	let r;
    if(req.query.order !== undefined) {
		if(req.query.order == "value") {
			r = obj.sort((a, b) => {
				return parseFloat(a.value.replace(/[^\d\.\,]/, "")) - parseFloat(b.value.replace(/[^\d\.\,]/, ""));
			})
		} else {
        	r = obj.sort(dynamicSort(req.query.order))
		}
	}
	if(req.query.customer !== undefined) {
		r = obj.filter(v => v.customer === req.query.customer);
	}
	if(req.query.convert !== undefined) {
		let currencies = {"EUR": "€", "USD": "$", "GBP": "£"};
		r.forEach((v) => {
			let value = v.value.replace(/[^\d\.\,]/, "");
    		v.value = value + " " + currencies[req.query.convert];
		})
	}
    return res.json(r);
});
