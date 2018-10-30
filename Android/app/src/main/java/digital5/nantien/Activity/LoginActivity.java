package digital5.nantien.Activity;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import digital5.nantien.HomePage;
import digital5.nantien.R;

public class LoginActivity extends AppCompatActivity {

     EditText username;
     EditText password;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        username = (EditText) findViewById(R.id.login_name);
        password = (EditText) findViewById(R.id.login_password);
        final Button loginButton = (Button) findViewById(R.id.login_button);
        final TextView registerLink = (TextView) findViewById(R.id.registerLink);


        registerLink.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {
                Intent registerIntent = new Intent(LoginActivity.this, RegisterActivity.class);
                LoginActivity.this.startActivity(registerIntent);
            }
        });

        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                final String name = username.getText().toString();
                final String pw = password.getText().toString();


                Response.Listener<String> responseListener = new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONObject jsonResponse = new JSONObject(response);
                            String hasError;

//                            boolean success = jsonResponse.getBoolean("success");

//                            if(success){
//                                Toast.makeText(LoginActivity.this, "Login Success!", Toast.LENGTH_LONG).show();
//                                Intent intent = new Intent(LoginActivity.this, HomePage.class);
//                                LoginActivity.this.startActivity(intent);
//
//                            }else{
//                                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
//                                builder.setMessage("Server Error!")
//                                        .setNegativeButton("Retry", null)
//                                        .create()
//                                        .show();
//                            }




                            hasError = jsonResponse.getString("status");
                            //Toast.makeText(LoginActivity.this, hasError, Toast.LENGTH_LONG).show();
                            if(hasError.equals("fail")){
                                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                                builder.setMessage("Error! User already exists")
                                        .setNegativeButton("Retry", null)
                                        .create()
                                        .show();

                            }else{
                              //  Toast.makeText(LoginActivity.this, "Login Success!", Toast.LENGTH_LONG).show();
                                Intent intent = new Intent(LoginActivity.this, HomePage.class);
                                LoginActivity.this.startActivity(intent);
                            }




                        } catch (JSONException e) {
                            e.printStackTrace();

                        }

                    }
                };

                LoginRequest loginRequest = new LoginRequest(name, pw, responseListener);
                RequestQueue queue = Volley.newRequestQueue(LoginActivity.this);
                queue.add(loginRequest);
                saveinfo();

            }
        });

    }


    public void saveinfo(){

        SharedPreferences sharedPref = getSharedPreferences("userinfo", Context.MODE_PRIVATE);

        SharedPreferences.Editor editor = sharedPref.edit();
        editor.putString("username", username.getText().toString());
        editor.putString("password", password.getText().toString());
        editor.apply();

        Toast.makeText(this, "Saved after Login!", Toast.LENGTH_LONG).show();
    }
}
