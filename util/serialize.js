function getFormJson(form) {
    // Select Form
    let selForm = document.querySelector(form);

    // Getting an FormData 
    let data = new FormData(selForm);

    // Getting a Serialize Data from FormData
    let serializedFormData = serialize(data);Ï

    // Log 
    console.log(JSON.stringify(serializedFormData));
}

function serialize(rawData) {
    let rtnData = {};
    for (let [key, value] of rawData) {
        let isArrayValue = key.endsWith("[]");

        // Array Values
        if (isArrayValue) {
            key = key.slice(0, -2); // "[]" 제거

            if (rtnData[key] === undefined) {
                rtnData[key] = [];
            }
            rtnData[key].push(value);
        }
        // Other 
        else {
            rtnData[key] = value;
        }
    }

    return rtnData;
}
