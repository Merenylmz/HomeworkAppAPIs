const Users = require("../models/Users");
const jwt = require("jsonwebtoken");

async function verifyToken(token){
    const decodedToken = jwt.verify(token, process.env.PrivateKey);
    
    const user = await Users.findOne({_id: decodedToken.userId});
    if(!user){return {status:"not Succesfuly"}};

    return {status: "OK", user};
};

module.exports = verifyToken;