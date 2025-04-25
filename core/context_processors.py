def user_roles(request):
    if request.user.is_authenticated:
        return {
            "is_admin": request.user.groups.filter(name="admins").exists(),
            "is_preparation": request.user.groups.filter(name="preparations").exists(),
            "is_line": request.user.groups.filter(name="lines").exists(),
            "is_kikinda": request.user.groups.filter(name="kikinda").exists(),
            "is_senta": request.user.groups.filter(name="senta").exists(),
        }
    return {"is_admin": False, "is_preparation": False, "is_line": False, "is_kikinda": False, "is_senta": False}
