const mongoose = require("mongoose");

const homeworkSchema = mongoose.Schema({
    homeworkId: {type: mongoose.Schema.Types.ObjectId, ref: "homeworks"},
    title: String
});
const notificationSchema = mongoose.Schema({
    notificationId: {type: mongoose.Schema.Types.ObjectId, ref: "notifications"}
});

const userSchema = mongoose.Schema({
    name: String,
    email: String,
    password: String, 
    profilePhoto: {type: String, default: null},
    bioTxt: {type: String, default: null},
    homeworks: {type: [homeworkSchema], default: []},
    notifications: {type: [notificationSchema], default:[]},
    isAdmin: {type: Boolean, default: false},
    resetToken: {type: String, default: null},
    resetTokenExpiration: {type: Date, default: null},
});


const Users = mongoose.model("Users", userSchema);


module.exports = Users;