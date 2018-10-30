package digital5.nantien;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import digital5.nantien.Activity.LoginActivity;
import digital5.nantien.Activity.MainActivity;
import digital5.nantien.Activity.RegisterActivity;

public class feedback extends MainActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getLayoutInflater().inflate(R.layout.activity_feedback, frameLayout);
        toolbar.setTitle("Feedback");

        final EditText feedback_input = (EditText) findViewById(R.id.feedback_input);
        final Button feedback_submit = (Button) findViewById(R.id.feedback_button);


        feedback_submit.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {
                Toast.makeText(feedback.this, "Submitted Feedback", Toast.LENGTH_LONG).show();
                finish();
                startActivity(getIntent());
            }
        });

    }


    @Override
    protected void onResume() {
        super.onResume();
        // to check current activity in the navigation drawer
        navigationView.getMenu().getItem(4).setChecked(true);
    }


}
