package digital5.nantien.Activity;


import com.android.volley.Response;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;

import digital5.nantien.Models.API_urls;

public class LoginRequest extends StringRequest {

    static API_urls api_url = new API_urls();
    private static final String REGISTER_REQUEST_URL = "http://ntt.07.gs/api/auth/login"; //api_url.registerDetail();

    private Map<String, String> params;

    public LoginRequest( String username, String password,  Response.Listener<String> listener) {
        super(Method.POST, REGISTER_REQUEST_URL , listener, null);

        params = new HashMap<>();
        params.put("username", username);
        params.put("password", password);


    }

    @Override
    public Map<String, String> getParams(){
        return params;
    }





}
