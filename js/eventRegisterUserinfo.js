let userNickname = document.getElementById("nickname");
let userDob = document.getElementById("dob");
let userPfp = document.getElementById("userpfp");
if (userNickname || userDob || userPfp) {
    userNickname.addEventListener("input", validateProfileNickname);
    userDob.addEventListener("input", validateDOB);
    userPfp.addEventListener("change", validateAvatar);
}